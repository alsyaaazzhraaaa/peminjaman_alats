<?php

use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\Notification;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running Test Denda...\n";

try {
    // 1. Create Data
    $kategori = Kategori::firstOrCreate(['nama_kategori' => 'Kategori Test']);
    
    $alat = Alat::create([
        'id_kategori' => $kategori->id,
        'nama_alat' => 'Laptop Test ' . rand(1,1000),
        'jumlah' => 10,
        'harga' => 5000000, // 5 Juta
        'kondisi' => 'Baik',
        'status' => 'tersedia'
    ]);
    
    $user = User::where('role', 'peminjam')->first();
    if(!$user) {
         $user = User::create([
             'username' => 'testuser',
             'password' => bcrypt('password'),
             'role' => 'peminjam'
         ]);
    }
    
    $admin = User::where('role', 'admin')->first();
    if(!$admin) {
        $admin = User::create([
             'username' => 'testadmin',
             'password' => bcrypt('password'),
             'role' => 'admin'
         ]);
    }

    // 2. Create Peminjaman
    $peminjaman = Peminjaman::create([
        'id_user' => $user->id,
        'tanggal_pinjam' => now()->subDays(3)->format('Y-m-d'),
        'tanggal_kembali_rencana' => now()->startOfDay()->subDays(1)->format('Y-m-d'), // 1 Day late
        'status' => 'menunggu'
    ]);
    
    $detail = DetailPeminjaman::create([
        'id_peminjaman' => $peminjaman->id,
        'id_alat' => $alat->id,
        'jumlah_pinjam' => 2
    ]);
    
    // 3. Approve
    $peminjaman->approve($admin->id);
    
    // Wait, let's verify stock was reduced
    $alat->refresh();
    echo "Stok setelah approve (Seharusnya 8): " . $alat->jumlah . "\n";
    
    // 4. Pengembalian: 1 Hilang, 1 Baik, Telat 1 hari
    // Denda telat: 1 hari * 5000 = 5000
    // Denda hilang: 1 * 5000000 = 5000000
    // Total Denda: 5005000
    
    $kondisiItems = [
        [
            'id_detail' => $detail->id,
            'jumlah_baik' => 1,
            'jumlah_rusak' => 0,
            'jumlah_hilang' => 1,
        ]
    ];
    
    $pengembalian = $peminjaman->returnItems(now()->format('Y-m-d'), $kondisiItems, 'Test dikembalikan');
    
    $alat->refresh();
    echo "Stok setelah return 1 baik, 1 hilang (Seharusnya 9): " . $alat->jumlah . "\n";
    echo "Total Denda: " . $pengembalian->denda . " (Seharusnya 5005000)\n";
    echo "Status Pembayaran: " . $pengembalian->status_pembayaran . " (Seharusnya belum_bayar)\n";
    
    $notifications = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $user->id)->get();
    echo "Jumlah Notifikasi Peminjam: " . count($notifications) . "\n";
    
    // Cleanup
    $pengembalian->delete();
    $detail->delete();
    $peminjaman->delete();
    $alat->delete();
    
    echo "Test Completed Successfully!\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

