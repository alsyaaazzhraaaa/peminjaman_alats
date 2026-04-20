<?php

namespace App\Filament\Admin\Resources\PengembalianResource\Pages;

use App\Filament\Admin\Resources\PengembalianResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPengembalian extends ViewRecord
{
    protected static string $resource = PengembalianResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
