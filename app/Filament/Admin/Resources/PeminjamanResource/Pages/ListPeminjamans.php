<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamans extends ListRecords
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
