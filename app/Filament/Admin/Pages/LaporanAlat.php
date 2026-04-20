<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use App\Models\Alat;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class LaporanAlat extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Alat';
    protected static ?string $slug = 'laporan-alat';

    protected string $view = 'filament.pages.laporan-blank';

    public ?string $pdfData = null;
    public ?string $filterKategori = null;
    public ?string $filterKondisi  = null;
    public ?string $filterStatus   = null;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role !== 'peminjam';
    }

    public function downloadPdf(): mixed
    {
        $alatQuery = Alat::with('kategori');

        if ($this->filterKategori) {
            $alatQuery->where('id_kategori', $this->filterKategori);
        }
        if ($this->filterKondisi) {
            $alatQuery->where('kondisi', $this->filterKondisi);
        }
        if ($this->filterStatus) {
            $alatQuery->where('status', $this->filterStatus);
        }

        $alat = $alatQuery->get();
        $pdf  = Pdf::loadView('pdf.laporan-alat', ['data' => $alat]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-alat-' . Carbon::now()->format('Ymd') . '.pdf');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_pdf')
                ->label('Cetak PDF')
                ->color('primary')
                ->modalHeading('Filter Laporan Data Alat')
                ->modalSubmitActionLabel('Tampilkan Preview')
                ->form([
                    Select::make('id_kategori')
                        ->label('Kategori')
                        ->options(Kategori::pluck('nama_kategori', 'id'))
                        ->placeholder('Semua Kategori'),
                    Select::make('kondisi')
                        ->label('Kondisi')
                        ->options([
                            'baik'  => 'Baik',
                            'rusak' => 'Rusak',
                            'hilang' => 'Hilang',
                        ])
                        ->placeholder('Semua Kondisi'),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'tersedia' => 'Tersedia',
                            'dipinjam' => 'Dipinjam',
                            'rusak'    => 'Rusak',
                        ])
                        ->placeholder('Semua Status'),
                ])
                ->action(function (array $data) {
                    $this->filterKategori = $data['id_kategori'] ?? null;
                    $this->filterKondisi  = $data['kondisi'] ?? null;
                    $this->filterStatus   = $data['status'] ?? null;

                    $alatQuery = Alat::with('kategori');

                    if ($this->filterKategori) {
                        $alatQuery->where('id_kategori', $this->filterKategori);
                    }
                    if ($this->filterKondisi) {
                        $alatQuery->where('kondisi', $this->filterKondisi);
                    }
                    if ($this->filterStatus) {
                        $alatQuery->where('status', $this->filterStatus);
                    }

                    $alat = $alatQuery->get();
                    $pdf  = Pdf::loadView('pdf.laporan-alat', ['data' => $alat]);
                    
                    $this->pdfData = base64_encode($pdf->output());
                }),
        ];
    }
}
