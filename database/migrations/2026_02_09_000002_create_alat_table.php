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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategori')->cascadeOnDelete();
            $table->string('nama_alat');
            $table->integer('jumlah');
            $table->string('kondisi');
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
