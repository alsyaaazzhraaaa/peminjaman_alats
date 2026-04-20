<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $table = 'kategori';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];


    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class, 'id_kategori');
    }
}
