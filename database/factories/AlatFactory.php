<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alat>
 */
class AlatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_alat' => fake()->word(),
            'deskripsi' => fake()->sentence(),
            'id_kategori' => Kategori::factory(),
            'jumlah' => fake()->numberBetween(1, 100),
            'harga_sewa_per_hari' => fake()->numberBetween(10000, 50000),
            'status' => 'tersedia',
        ];
    }
}
