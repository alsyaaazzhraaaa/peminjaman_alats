<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlatResource\Pages;
use App\Models\Alat;
use App\Models\Kategori;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\TextSize;

class AlatResource extends Resource
{
    protected static ?string $model = Alat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Alat';

    protected static ?string $pluralModelLabel = 'Alat';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->description('Masukkan detail dasar alat yang akan didaftarkan.')
                    ->schema([
                        TextInput::make('nama_alat')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-wrench')
                            ->placeholder('Contoh: Bor Listrik, Proyektor...'),
                        Select::make('id_kategori')
                            ->label('Kategori')
                            ->options(Kategori::all()->pluck('nama_kategori', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nama_kategori')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->prefixIcon('heroicon-o-tag'),
                        TextInput::make('harga')
                            ->label('Harga Barang (Rp)')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix('Rp')
                            ->helperText('Digunakan untuk perhitungan denda hilang/rusak.'),
                    ])->columns(2),

                Section::make('Ketersediaan & Kondisi')
                    ->description('Atur jumlah stok dan kondisi fisik alat.')
                    ->schema([
                        TextInput::make('jumlah')
                            ->label('Stok Awal')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(0)
                            ->prefixIcon('heroicon-o-calculator'),
                        Select::make('kondisi')
                            ->label('Kondisi Fisik')
                            ->options([
                                'Baik' => 'Baik',
                                'Rusak Ringan' => 'Rusak Ringan',
                                'Rusak Berat' => 'Rusak Berat',
                                'Perlu Perbaikan' => 'Perlu Perbaikan',
                            ])
                            ->required()
                            ->default('Baik')
                            ->native(false),
                        Select::make('status')
                            ->label('Status Ketersediaan')
                            ->options([
                                'tersedia' => 'Tersedia',
                                'dipinjam' => 'Tidak Tersedia / Dipinjam',
                            ])
                            ->required()
                            ->default('tersedia')
                            ->native(false),
                    ])->columns(3),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Alat')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('nama_alat')
                                    ->label('Nama Alat')
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->icon('heroicon-o-wrench'),
                                TextEntry::make('kategori.nama_kategori')
                                    ->label('Kategori')
                                    ->badge()
                                    ->color('info')
                                    ->icon('heroicon-o-tag'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('jumlah')
                                    ->label('Sisa Stok')
                                    ->badge()
                                    ->color(fn(int $state): string => $state > 0 ? 'success' : 'danger'),
                                TextEntry::make('kondisi')
                                    ->label('Kondisi Fisik')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'Baik' => 'success',
                                        'Rusak Ringan', 'Perlu Perbaikan' => 'warning',
                                        default => 'danger',
                                    }),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => $state === 'tersedia' ? 'success' : 'danger'),
                                TextEntry::make('harga')
                                    ->label('Harga Barang')
                                    ->money('IDR', locale: 'id'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_alat')
                    ->label('Nama Alat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('kondisi')
                    ->label('Kondisi'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dipinjam' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori')
                    ->label('Kategori')
                    ->options(Kategori::all()->pluck('nama_kategori', 'id')),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlats::route('/'),
            'create' => Pages\CreateAlat::route('/create'),
            'view' => Pages\ViewAlat::route('/{record}'),
            'edit' => Pages\EditAlat::route('/{record}/edit'),
        ];
    }
}
