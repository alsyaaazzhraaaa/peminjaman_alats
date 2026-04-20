<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{

    public $timestamps = false;


    protected $table = 'detail_peminjaman';


    protected $fillable = [
        'id_peminjaman',
        'id_alat',
        'jumlah_pinjam',
    ];


    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }


    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }
}
