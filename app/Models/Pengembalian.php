<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengembalian extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $table = 'pengembalian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_peminjaman',
        'tanggal_kembali',
        'denda',
        'kondisi_kembali',
        'status_pembayaran',
    ];


    protected function casts(): array
    {
        return [
            'tanggal_kembali' => 'date',
        ];
    }


    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }


    public function getFormattedDendaAttribute(): string
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }


    public function hasFine(): bool
    {
        return $this->denda > 0;
    }
}
