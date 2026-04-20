<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengembalianResource\Pages;
use App\Models\Pengembalian;
use BackedEnum;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\TextSize;

class PengembalianResource extends Resource
{
    protected static ?string $model = Pengembalian::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Pengembalian';

    protected static ?string $pluralModelLabel = 'Pengembalian';


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();


        if (auth()->user()->isPeminjam()) {
            $query->whereHas('peminjaman', function ($q) {
                $q->where('id_user', auth()->id());
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengembalian')
                    ->description('Detail pengembalian alat dan denda.')
                    ->schema([
                        Select::make('id_peminjaman')
                            ->label('ID Peminjaman')
                            ->relationship('peminjaman', 'id')
                            ->required()
                            ->disabled()
                            ->prefixIcon('heroicon-o-hashtag'),
                        DatePicker::make('tanggal_kembali')
                            ->label('Tanggal Kembali')
                            ->required()
                            ->maxDate(now())
                            ->prefixIcon('heroicon-o-calendar'),
                        TextInput::make('kondisi_kembali')
                            ->label('Kondisi Kembali')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-clipboard-document-check'),
                        TextInput::make('denda')
                            ->label('Denda Keterlambatan')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->prefix('Rp')
                            ->prefixIcon('heroicon-o-currency-dollar'),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pengembalian')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('peminjaman.user.nama')
                                    ->label('Peminjam')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold'),
                                TextEntry::make('tanggal_kembali')
                                    ->label('Tanggal Kembali')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),
                                TextEntry::make('kondisi_kembali')
                                    ->label('Kondisi Alat')
                                    ->badge()
                                    ->color(fn($state) => strtolower($state) === 'baik' ? 'success' : 'warning'),
                            ]),
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('denda')
                                    ->label('Total Denda')
                                    ->money('IDR')
                                    ->size(TextSize::Large)
                                    ->weight('bold')
                                    ->color(fn(int $state) => $state > 0 ? 'danger' : 'success')
                                    ->icon('heroicon-o-currency-dollar'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('peminjaman.id')
                    ->label('ID Peminjaman')
                    ->sortable(),
                Tables\Columns\TextColumn::make('peminjaman.user.username')
                    ->label('Peminjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Tanggal Kembali')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kondisi_kembali')
                    ->label('Kondisi'),
                Tables\Columns\TextColumn::make('denda')
                    ->label('Denda')
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal_kembali')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->maxDate(now()),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_kembali', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_kembali', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([

            ])
            ->defaultSort('tanggal_kembali', 'desc');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalians::route('/'),
            'view' => Pages\ViewPengembalian::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {

        return false;
    }
}
