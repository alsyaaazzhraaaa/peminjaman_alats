<x-filament-panels::page>
    <form wire:submit="cetakPdf" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit" color="primary">
                Cetak Laporan PDF
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
