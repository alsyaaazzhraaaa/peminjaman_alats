<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (auth()->user()->isPeminjam()) {
            $data['id_user'] = auth()->id();
        }

        $data['status'] = 'menunggu';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
