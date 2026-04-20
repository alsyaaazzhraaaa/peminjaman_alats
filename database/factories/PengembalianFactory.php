<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengembalian>
 */
class PengembalianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_peminjaman' => Peminjaman::factory(),
            'tanggal_kembali' => now(),
            'kondisi_kembali' => 'baik',
            'denda' => 0,
        ];
    }
}
