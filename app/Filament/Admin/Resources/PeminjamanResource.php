<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PeminjamanResource\Pages;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\TextSize;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Peminjaman';

    protected static ?string $pluralModelLabel = 'Peminjaman';

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'peminjamans';
    }



    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();


        if (auth()->user()->isPeminjam()) {
            $query->where('id_user', auth()->id());
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peminjaman')
                    ->description('Detail peminjam dan waktu peminjaman.')
                    ->schema([
                        Select::make('id_user')
                            ->label('Peminjam')
                            ->options(User::where('role', 'peminjam')->pluck('username', 'id'))
                            ->required()
                            ->searchable()
                            ->default(fn() => auth()->user()->isPeminjam() ? auth()->id() : null)
                            ->disabled(fn() => auth()->user()->isPeminjam())
                            ->prefixIcon('heroicon-o-user'),
                        DatePicker::make('tanggal_pinjam')
                            ->label('Tanggal Pinjam')
                            ->required()
                            ->default(now())
                            ->maxDate(now())
                            ->prefixIcon('heroicon-o-calendar'),
                        DatePicker::make('tanggal_kembali_rencana')
                            ->label('Tanggal Kembali (Rencana)')
                            ->required()
                            ->after('tanggal_pinjam')
                            ->prefixIcon('heroicon-o-calendar-days'),
                        Select::make('status')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'dikembalikan' => 'Dikembalikan',
                            ])
                            ->default('menunggu')
                            ->disabled()
                            ->dehydrated()
                            ->prefixIcon('heroicon-o-information-circle'),
                    ])->columns(2),

                Section::make('Detail Alat yang Dipinjam')
                    ->description('Daftar alat yang akan dipinjam.')
                    ->schema([
                        Repeater::make('detailPeminjaman')
                            ->relationship()
                            ->schema([
                                Select::make('id_alat')
                                    ->label('Alat')
                                    ->options(
                                        Alat::where('status', 'tersedia')
                                            ->where('jumlah', '>', 0)
                                            ->get()
                                            ->mapWithKeys(fn($alat) => [
                                                $alat->id => "{$alat->nama_alat} (Stok: {$alat->jumlah})"
                                            ])
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->distinct()
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-wrench'),
                                TextInput::make('jumlah_pinjam')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(fn(Get $get) => Alat::find($get('id_alat'))?->jumlah ?? 1)
                                    ->columnSpan(1)
                                    ->prefixIcon('heroicon-o-calculator'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Alat')
                            ->reorderable(false)
                            ->required()
                            ->minItems(1),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Status Peminjaman')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status Terkini')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'menunggu' => 'warning',
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger',
                                        'dikembalikan' => 'info',
                                        default => 'gray',
                                    })
                                    ->size(TextSize::Large),
                                TextEntry::make('user.username')
                                    ->label('Peminjam')
                                    ->icon('heroicon-o-user'),
                                TextEntry::make('tanggal_pinjam')
                                    ->label('Tgl Pinjam')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),
                                TextEntry::make('tanggal_kembali_rencana')
                                    ->label('Rencana Kembali')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar-days'),
                            ]),
                    ]),

                Section::make('Daftar Alat Dipinjam')
                    ->schema([
                        RepeatableEntry::make('detailPeminjaman')
                            ->label('')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('alat.nama_alat')
                                            ->label('Alat')
                                            ->weight('bold')
                                            ->icon('heroicon-o-wrench'),
                                        TextEntry::make('jumlah_pinjam')
                                            ->label('Jumlah')
                                            ->badge(),
                                        TextEntry::make('alat.kondisi')
                                            ->label('Kondisi Alat'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tgl Pinjam')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->label('Tgl Kembali (Rencana)')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'dikembalikan' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('estimasi_denda')
                    ->label('Estimasi Denda')
                    ->getStateUsing(function (Peminjaman $record) {
                        if (!$record->isApproved()) return '—';
                        $hariTerlambat = $record->hari_terlambat;
                        if ($hariTerlambat <= 0) return '—';
                        return 'Rp ' . number_format($record->estimasi_denda, 0, ',', '.') . " ($hariTerlambat hari)";
                    })
                    ->badge()
                    ->color(fn ($state) => $state === '—' ? 'gray' : 'danger')
                    ->visible(fn() => auth()->user()->isStaff() || auth()->user()->isPeminjam()),
                Tables\Columns\TextColumn::make('approver.username')
                    ->label('Disetujui Oleh')
                    ->default('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'dikembalikan' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Peminjam')
                    ->options(User::where('role', 'peminjam')->pluck('username', 'id')),
                Tables\Filters\Filter::make('tanggal_kembali_rencana')
                    ->form([
                        DatePicker::make('from')
                            ->label('Rencana Kembali (Dari)')
                            ->maxDate(now()),
                        DatePicker::make('until')
                            ->label('Rencana Kembali (Sampai)')
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_kembali_rencana', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_kembali_rencana', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn(Peminjaman $record): bool => $record->isPending()),


                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui peminjaman ini? Stok alat akan berkurang secara otomatis.')
                    ->visible(fn(Peminjaman $record): bool => $record->isPending() && auth()->user()->isStaff())
                    ->action(function (Peminjaman $record) {

                        foreach ($record->detailPeminjaman as $detail) {
                            if ($detail->alat->jumlah < $detail->jumlah_pinjam) {
                                Notification::make()
                                    ->title('Stok tidak mencukupi')
                                    ->body("Stok {$detail->alat->nama_alat} tidak mencukupi (tersedia: {$detail->alat->jumlah}, diminta: {$detail->jumlah_pinjam})")
                                    ->danger()
                                    ->send();
                                return;
                            }
                        }

                        $record->approve(auth()->id());

                        Notification::make()
                            ->title('Peminjaman Disetujui')
                            ->body('Peminjaman berhasil disetujui dan stok telah dikurangi.')
                            ->success()
                            ->send();
                    }),


                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menolak peminjaman ini?')
                    ->visible(fn(Peminjaman $record): bool => $record->isPending() && auth()->user()->isStaff())
                    ->action(function (Peminjaman $record) {
                        $record->reject();

                        Notification::make()
                            ->title('Peminjaman Ditolak')
                            ->body('Peminjaman berhasil ditolak.')
                            ->warning()
                            ->send();
                    }),


                Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('info')
                    ->visible(fn(Peminjaman $record): bool => $record->isApproved())
                    ->form(function(Peminjaman $record) {
                        return [
                            DatePicker::make('tanggal_kembali')
                                ->label('Tanggal Kembali')
                                ->required()
                                ->default(now())
                                ->maxDate(now()),
                            Repeater::make('kondisiItems')
                                ->label('Kondisi Item Berdasarkan Peminjaman')
                                ->default(function() use ($record) {
                                    return $record->detailPeminjaman->map(function($detail) {
                                        return [
                                            'id_detail' => $detail->id,
                                            'nama_alat' => $detail->alat->nama_alat . " (Harga: " . number_format($detail->alat->harga ?? 0, 0, ',', '.') . ")",
                                            'jumlah_pinjam' => $detail->jumlah_pinjam,
                                            'jumlah_baik' => $detail->jumlah_pinjam,
                                            'jumlah_rusak' => 0,
                                            'jumlah_hilang' => 0,
                                        ];
                                    })->toArray();
                                })
                                ->schema([
                                    TextInput::make('id_detail')->hidden(),
                                    TextInput::make('nama_alat')
                                        ->label('Alat')
                                        ->disabled()
                                        ->columnSpan(2),
                                    TextInput::make('jumlah_pinjam')
                                        ->label('Total Pinjam')
                                        ->disabled()
                                        ->columnSpan(1),
                                    Grid::make(3)->schema([
                                        TextInput::make('jumlah_baik')
                                            ->label('Jml Baik')
                                            ->numeric()
                                            ->required()
                                            ->default(0),
                                        TextInput::make('jumlah_rusak')
                                            ->label('Jml Rusak (50%)')
                                            ->numeric()
                                            ->required()
                                            ->default(0),
                                        TextInput::make('jumlah_hilang')
                                            ->label('Jml Hilang (100%)')
                                            ->numeric()
                                            ->required()
                                            ->default(0),
                                    ])->columnSpan(3),
                                ])
                                ->columns(3)
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement(),
                            TextInput::make('catatan')
                                ->label('Catatan Pengembalian')
                                ->default('-'),
                            Placeholder::make('info_denda')
                                ->label('Informasi Keterlambatan')
                                ->content(function(Peminjaman $record, Get $get) {
                                    $tglKembali = $get('tanggal_kembali') ?? now()->format('Y-m-d');
                                    $plannedDate = $record->tanggal_kembali_rencana;
                                    $daysLate = \Carbon\Carbon::parse($tglKembali)->diffInDays($plannedDate, false);
                                    
                                    $pesan = "Rencana kembali: " . \Carbon\Carbon::parse($plannedDate)->format('d M Y') . ". ";
                                    
                                    if ($daysLate < 0) {
                                        $denda = abs($daysLate) * 5000;
                                        return $pesan . "Terlambat " . abs($daysLate) . " hari. Denda: Rp " . number_format($denda, 0, ',', '.');
                                    }
                                    
                                    return $pesan . "Tepat waktu (tidak ada denda dari keterlambatan).";
                                }),
                        ];
                    })
                    ->action(function (Peminjaman $record, array $data) {
                        try {
                            $pengembalian = $record->returnItems(
                                $data['tanggal_kembali'],
                                $data['kondisiItems'],
                                $data['catatan']
                            );

                            if ($pengembalian) {
                                $message = 'Pengembalian berhasil dicatat.';
                                if ($pengembalian->denda > 0) {
                                    $message .= " Denda Sebesar: Rp " . number_format($pengembalian->denda, 0, ',', '.') . " harus dibayar.";
                                }

                                Notification::make()
                                    ->title('Pengembalian Berhasil')
                                    ->body($message)
                                    ->success()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal mengembalikan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('bayar_denda')
                    ->label('Konfirmasi Denda')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn(Peminjaman $record): bool => $record->status === 'dikembalikan' && optional($record->pengembalian)->status_pembayaran === 'belum_bayar')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran Tunai')
                    ->modalDescription(fn(Peminjaman $record) => 'Pastikan Anda telah menerima uang tunai sebesar Rp ' . number_format($record->pengembalian->denda, 0, ',', '.') . ' dari peminjam. Lanjutkan?')
                    ->action(function(Peminjaman $record) {
                        $pengembalian = $record->pengembalian;
                        if($pengembalian) {
                            $pengembalian->status_pembayaran = 'lunas';
                            $pengembalian->save();
                            
                            Notification::make()
                                ->title('Pembayaran Denda Diterima')
                                ->body('Denda atas nama ' . $record->user->username . ' telah dilunasi.')
                                ->success()
                                ->send();
                        }
                    }),

                DeleteAction::make()
                    ->visible(fn(Peminjaman $record): bool => $record->isPending()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamans::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'view' => Pages\ViewPeminjaman::route('/{record}'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
