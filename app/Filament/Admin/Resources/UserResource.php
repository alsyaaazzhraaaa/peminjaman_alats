<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->description('Kelola detail login dan peran pengguna.')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-user')
                            ->placeholder('Nama Lengkap User'),
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-at-symbol')
                            ->placeholder('username_unik'),
                        Select::make('role')
                            ->label('Peran (Role)')
                            ->options([
                                'admin' => 'Admin - Akses Penuh',
                                'petugas' => 'Petugas - Manajemen Peminjaman',
                                'peminjam' => 'Peminjam',
                            ])
                            ->required()
                            ->default('peminjam')
                            ->native(false)
                            ->prefixIcon('heroicon-o-shield-check'),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn(string $state): string => bcrypt($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-key')
                            ->helperText('Kosongkan jika tidak ingin mengubah password.'),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Pengguna')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('nama')
                                    ->label('Nama Lengkap')
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->icon('heroicon-o-user'),
                                TextEntry::make('username')
                                    ->label('Username')
                                    ->icon('heroicon-o-at-symbol')
                                    ->copyable(),
                                TextEntry::make('role')
                                    ->label('Role')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'admin' => 'danger',
                                        'petugas' => 'warning',
                                        'peminjam' => 'success',
                                        default => 'gray',
                                    })
                                    ->icon('heroicon-o-shield-check'),
                                TextEntry::make('created_at')
                                    ->label('Bergabung Sejak')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'petugas' => 'warning',
                        'peminjam' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'petugas' => 'Petugas',
                        'peminjam' => 'Peminjam',
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
