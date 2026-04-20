<?php

namespace App\Filament\Admin\Resources\KategoriResource\Pages;

use App\Filament\Admin\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategori extends ViewRecord
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
