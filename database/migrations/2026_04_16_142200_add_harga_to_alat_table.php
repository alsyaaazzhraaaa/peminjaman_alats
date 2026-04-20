<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->integer('harga')->default(0)->after('id_kategori');
        });
    }

    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
