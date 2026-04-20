<?php

namespace App\Filament\Admin\Resources\AlatResource\Pages;

use App\Filament\Admin\Resources\AlatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAlat extends CreateRecord
{
    protected static string $resource = AlatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
