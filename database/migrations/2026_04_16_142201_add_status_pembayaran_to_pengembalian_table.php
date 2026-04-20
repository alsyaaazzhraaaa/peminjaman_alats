<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['lunas', 'belum_bayar', 'menunggu_verifikasi', 'tidak_ada'])->default('tidak_ada')->after('kondisi_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }
};
