<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\Peminjaman;

class PeminjamanObserver
{

    public function created(Peminjaman $peminjaman): void
    {
        $userId = auth()->id() ?? $peminjaman->id_user;

        LogAktivitas::create([
            'id_user' => $userId,
            'aktivitas' => "Membuat peminjaman baru #{$peminjaman->id_peminjaman}",
            'waktu' => now(),
        ]);
    }


    public function updated(Peminjaman $peminjaman): void
    {
        $userId = auth()->id() ?? $peminjaman->id_user;

        $statusMessages = [
            'disetujui' => "Peminjaman #{$peminjaman->id_peminjaman} telah disetujui",
            'ditolak' => "Peminjaman #{$peminjaman->id_peminjaman} telah ditolak",
            'dikembalikan' => "Peminjaman #{$peminjaman->id_peminjaman} telah dikembalikan",
        ];

        if ($peminjaman->isDirty('status')) {
            $message = $statusMessages[$peminjaman->status] ?? "Mengupdate peminjaman #{$peminjaman->id_peminjaman}";
        } else {
            $message = "Mengupdate data peminjaman #{$peminjaman->id_peminjaman}";
        }

        LogAktivitas::create([
            'id_user' => $userId,
            'aktivitas' => $message,
            'waktu' => now(),
        ]);
    }


    public function deleted(Peminjaman $peminjaman): void
    {
        $userId = auth()->id();

        if ($userId) {
            LogAktivitas::create([
                'id_user' => $userId,
                'aktivitas' => "Menghapus peminjaman #{$peminjaman->id_peminjaman}",
                'waktu' => now(),
            ]);
        }
    }
}
