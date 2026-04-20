<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'])->default('menunggu');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
