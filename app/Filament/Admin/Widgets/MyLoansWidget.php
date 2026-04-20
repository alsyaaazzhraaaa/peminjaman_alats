<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Peminjaman;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyLoansWidget extends BaseWidget
{
    protected static ?string $heading = 'Peminjaman Saya';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peminjaman::query()
                    ->where('id_user', auth()->id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->label('Rencana Kembali')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'dikembalikan' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('detailPeminjaman')
                    ->label('Jumlah Alat')
                    ->getStateUsing(fn(Peminjaman $record) => $record->detailPeminjaman->count() . ' alat'),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->url(fn(Peminjaman $record) => route('filament.admin.resources.peminjamans.view', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated(false);
    }
}
