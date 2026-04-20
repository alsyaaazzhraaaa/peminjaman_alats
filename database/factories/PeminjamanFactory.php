<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(2),
            'status' => 'menunggu',
        ];
    }
}
