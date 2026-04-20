<?php

namespace App\Filament\Admin\Resources\AlatResource\Pages;

use App\Filament\Admin\Resources\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlat extends EditRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
