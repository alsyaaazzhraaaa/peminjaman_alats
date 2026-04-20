<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Petugas Gudang',
            'username' => 'petugas',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'username' => 'peminjam1',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        User::create([
            'nama' => 'Siti Rahayu',
            'username' => 'peminjam2',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        User::create([
            'nama' => 'Ahmad Wijaya',
            'username' => 'peminjam3',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        // Create Kategori
        $kategoriElektronik = Kategori::create([
            'nama_kategori' => 'Elektronik',
            'keterangan' => 'Peralatan elektronik seperti laptop, proyektor, dll.',
        ]);

        $kategoriMekanik = Kategori::create([
            'nama_kategori' => 'Mekanik',
            'keterangan' => 'Peralatan mekanik seperti bor, gerinda, dll.',
        ]);

        $kategoriLabor = Kategori::create([
            'nama_kategori' => 'Laboratorium',
            'keterangan' => 'Peralatan laboratorium seperti mikroskop, pipet, dll.',
        ]);

        $kategoriOlahraga = Kategori::create([
            'nama_kategori' => 'Olahraga',
            'keterangan' => 'Peralatan olahraga seperti bola, raket, dll.',
        ]);

        $kategoriMultimedia = Kategori::create([
            'nama_kategori' => 'Multimedia',
            'keterangan' => 'Peralatan multimedia seperti kamera, mic, dll.',
        ]);

        // Create Alat
        $alatData = [
            // Elektronik
            ['id_kategori' => $kategoriElektronik->id_kategori, 'nama_alat' => 'Laptop ASUS', 'jumlah' => 10, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriElektronik->id_kategori, 'nama_alat' => 'Proyektor Epson', 'jumlah' => 5, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriElektronik->id_kategori, 'nama_alat' => 'Printer Canon', 'jumlah' => 3, 'kondisi' => 'Baik', 'status' => 'tersedia'],

            // Mekanik
            ['id_kategori' => $kategoriMekanik->id_kategori, 'nama_alat' => 'Bor Listrik Bosch', 'jumlah' => 8, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriMekanik->id_kategori, 'nama_alat' => 'Gerinda Tangan', 'jumlah' => 6, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriMekanik->id_kategori, 'nama_alat' => 'Mesin Las', 'jumlah' => 2, 'kondisi' => 'Baik', 'status' => 'tersedia'],

            // Laboratorium
            ['id_kategori' => $kategoriLabor->id_kategori, 'nama_alat' => 'Mikroskop', 'jumlah' => 15, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriLabor->id_kategori, 'nama_alat' => 'Pipet Ukur', 'jumlah' => 20, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriLabor->id_kategori, 'nama_alat' => 'Timbangan Digital', 'jumlah' => 5, 'kondisi' => 'Baik', 'status' => 'tersedia'],

            // Olahraga
            ['id_kategori' => $kategoriOlahraga->id_kategori, 'nama_alat' => 'Bola Voli', 'jumlah' => 10, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriOlahraga->id_kategori, 'nama_alat' => 'Raket Badminton', 'jumlah' => 12, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriOlahraga->id_kategori, 'nama_alat' => 'Bola Basket', 'jumlah' => 8, 'kondisi' => 'Baik', 'status' => 'tersedia'],

            // Multimedia
            ['id_kategori' => $kategoriMultimedia->id_kategori, 'nama_alat' => 'Kamera DSLR Canon', 'jumlah' => 4, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriMultimedia->id_kategori, 'nama_alat' => 'Tripod', 'jumlah' => 6, 'kondisi' => 'Baik', 'status' => 'tersedia'],
            ['id_kategori' => $kategoriMultimedia->id_kategori, 'nama_alat' => 'Microphone Condenser', 'jumlah' => 5, 'kondisi' => 'Baik', 'status' => 'tersedia'],
        ];

        foreach ($alatData as $alat) {
            Alat::create($alat);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin    - username: admin, password: password');
        $this->command->info('Petugas  - username: petugas, password: password');
        $this->command->info('Peminjam - username: peminjam1, password: password');
    }
}
