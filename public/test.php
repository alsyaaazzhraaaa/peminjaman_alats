<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Peminjaman::with(['user', 'approver'])->latest()->first();
if (!$p) {
    echo "No Peminjaman found";
    exit;
}
echo "Peminjaman ID: " . $p->id_peminjaman . "\n";
echo "User ID: " . $p->id_user . "\n";
echo "User is empty? " . ($p->user ? 'No' : 'Yes') . "\n";
if ($p->user) {
    echo "User Username: " . $p->user->username . "\n";
}
echo "Approver ID: " . $p->disetujui_oleh . "\n";
echo "Approver is empty? " . ($p->approver ? 'No' : 'Yes') . "\n";
if ($p->approver) {
    echo "Approver Username: " . $p->approver->username . "\n";
}
