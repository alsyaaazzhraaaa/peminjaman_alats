<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{

    public $timestamps = false;


    protected $table = 'log_aktivitas';


    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'aktivitas',
        'waktu',
    ];


    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
        ];
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->waktu) {
                $model->waktu = now();
            }
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }


    public static function log(string $aktivitas, ?int $userId = null): self
    {
        return static::create([
            'id_user' => $userId ?? auth()->id(),
            'aktivitas' => $aktivitas,
            'waktu' => now(),
        ]);
    }
}
