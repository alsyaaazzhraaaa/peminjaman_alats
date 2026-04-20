<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{

    use HasFactory, Notifiable;


    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'password',
        'role',
        'kelas',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function getFilamentName(): string
    {
        return $this->nama ?? $this->username;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['role'] ?? null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $role = $this->role ?? '';
        return strtolower($role) === strtolower($panel->getId());
    }


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }


    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }


    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isPetugas();
    }


    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_user');
    }


    public function approvedPeminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh');
    }


    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'id_user');
    }
}
