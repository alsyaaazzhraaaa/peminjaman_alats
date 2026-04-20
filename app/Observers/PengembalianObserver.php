<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\Pengembalian;

class PengembalianObserver
{

    public function created(Pengembalian $pengembalian): void
    {
        $userId = auth()->id() ?? $pengembalian->peminjaman->id_user;

        $denda = $pengembalian->denda > 0
            ? " dengan denda Rp " . number_format($pengembalian->denda, 0, ',', '.')
            : "";

        LogAktivitas::create([
            'id_user' => $userId,
            'aktivitas' => "Mencatat pengembalian untuk peminjaman #{$pengembalian->id_peminjaman}{$denda}",
            'waktu' => now(),
        ]);
    }


    public function updated(Pengembalian $pengembalian): void
    {
        $userId = auth()->id();

        if ($userId) {
            LogAktivitas::create([
                'id_user' => $userId,
                'aktivitas' => "Mengupdate data pengembalian #{$pengembalian->id_pengembalian}",
                'waktu' => now(),
            ]);
        }
    }
}
