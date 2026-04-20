<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $table = 'alat';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id_kategori',
        'nama_alat',
        'harga',
        'jumlah',
        'kondisi',
        'status',
    ];


    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }


    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }


    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_alat');
    }


    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->jumlah > 0;
    }


    public function getAvailableStockAttribute(): int
    {
        return $this->jumlah;
    }
}
