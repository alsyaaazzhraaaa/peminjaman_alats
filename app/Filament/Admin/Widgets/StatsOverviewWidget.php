<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Pengguna terdaftar')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Total Alat', Alat::count())
                ->description('Alat tersedia')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('success'),
            Stat::make('Total Kategori', Kategori::count())
                ->description('Kategori alat')
                ->icon('heroicon-o-tag')
                ->color('warning'),
            Stat::make('Peminjaman Aktif', Peminjaman::where('status', 'disetujui')->count())
                ->description('Sedang dipinjam')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info'),
        ];
    }
}
