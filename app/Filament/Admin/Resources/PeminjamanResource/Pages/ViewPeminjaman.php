<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPeminjaman extends ViewRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
