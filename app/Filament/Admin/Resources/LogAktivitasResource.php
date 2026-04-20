<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LogAktivitasResource\Pages;
use App\Models\LogAktivitas;
use BackedEnum;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LogAktivitasResource extends Resource
{
    protected static ?string $model = LogAktivitas::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Log Aktivitas';

    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Log')
                    ->schema([
                        TextInput::make('id_user')
                            ->label('User ID')
                            ->disabled(),
                        Textarea::make('aktivitas')
                            ->label('Aktivitas')
                            ->disabled()
                            ->columnSpanFull(),
                        DateTimePicker::make('waktu')
                            ->label('Waktu')
                            ->disabled(),
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
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.role')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'petugas' => 'warning',
                        'peminjam' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('aktivitas')
                    ->label('Aktivitas')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_user')
                    ->label('User')
                    ->relationship('user', 'username'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([

            ])
            ->defaultSort('waktu', 'desc');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogAktivitas::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
