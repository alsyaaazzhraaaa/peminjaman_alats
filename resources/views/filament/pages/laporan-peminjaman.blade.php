<x-filament-panels::page>
    <div class="mt-8">
        @if($this->pdfData)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Preview Laporan PDF</h2>
                <x-filament::button wire:click="downloadPdf" color="success">
                    Download PDF
                </x-filament::button>
            </div>
            <div class="-mx-4 sm:-mx-6 lg:-mx-8 xl:-mx-12 mt-6">
                <iframe
                    src="data:application/pdf;base64,{{ $this->pdfData }}"
                    style="width: 100%; height: 85vh; display: block; border: none;"
                    class="shadow-xl"
                ></iframe>
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-48 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-base">
                <span>Klik tombol <strong>Cetak PDF</strong> di atas, pilih filter (opsional), lalu preview akan muncul di sini.</span>
                <span class="mt-2 text-sm text-gray-400">Biarkan filter kosong untuk menampilkan semua data laporan peminjaman.</span>
            </div>
        @endif
    </div>
</x-filament-panels::page>
