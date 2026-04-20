<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = Illuminate\Support\Facades\Schema::getColumnListing('log_aktivitas');
if(empty($cols)) {
    echo "No columns found or table doesn't exist.";
} else {
    echo "Columns: " . implode(', ', $cols) . "\n";
}
