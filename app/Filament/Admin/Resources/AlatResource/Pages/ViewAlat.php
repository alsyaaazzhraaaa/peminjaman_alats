<?php

namespace App\Filament\Admin\Resources\AlatResource\Pages;

use App\Filament\Admin\Resources\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAlat extends ViewRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
