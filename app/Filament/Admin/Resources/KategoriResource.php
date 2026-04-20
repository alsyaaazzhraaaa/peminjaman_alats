<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriResource\Pages;
use App\Models\Kategori;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\TextSize;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->description('Tentukan nama dan keterangan untuk kategori alat ini.')
                    ->schema([
                        TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-tag')
                            ->placeholder('Contoh: Elektronik'),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->placeholder('Deskripsi singkat mengenai isi kategori ini...'),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kategori')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('nama_kategori')
                                    ->label('Nama Kategori')
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->icon('heroicon-o-tag'),
                                TextEntry::make('keterangan')
                                    ->label('Keterangan')
                                    ->markdown()
                                    ->prose()
                                    ->placeholder('Tidak ada keterangan'),
                                TextEntry::make('alat_count')
                                    ->label('Jumlah Alat Terkait')
                                    ->getStateUsing(fn($record) => $record->alat()->count())
                                    ->badge()
                                    ->color('info'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('alat_count')
                    ->label('Jumlah Alat')
                    ->counts('alat')
                    ->sortable(),
            ])
            ->filters([

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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'view' => Pages\ViewKategori::route('/{record}'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}
