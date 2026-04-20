<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class LaporanPengembalian extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Pengembalian';
    protected static ?string $slug = 'laporan-pengembalian';

    protected string $view = 'filament.pages.laporan-pengembalian';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role !== 'peminjam';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Sampai')
                    ->required()
                    ->afterOrEqual('start_date'),
            ])
            ->statePath('data');
    }

    public function cetakPdf()
    {
        $data = $this->form->getState();
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $pengembalian = Pengembalian::with(['peminjaman.user'])
            ->whereDate('tanggal_kembali', '>=', Carbon::parse($startDate))
            ->whereDate('tanggal_kembali', '<=', Carbon::parse($endDate))
            ->get();

        $pdf = Pdf::loadView('pdf.laporan-pengembalian', [
            'data' => $pengembalian,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-pengembalian-' . Carbon::now()->format('Ymd') . '.pdf');
    }
}
