<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use App\Models\Alat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class LaporanAlat extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Alat';
    protected static ?string $slug = 'laporan-alat';

    protected string $view = 'filament.pages.laporan-blank';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role !== 'peminjam';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->action(function () {
                    $alat = Alat::with('kategori')->get();
                    $pdf = Pdf::loadView('pdf.laporan-alat', ['data' => $alat]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'laporan-alat.pdf');
                }),
        ];
    }
}
