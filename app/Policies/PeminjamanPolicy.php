<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeminjamanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

        return true;
    }

    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->isStaff()) {
            return true;
        }
        return $user->id === $peminjaman->id_user;
    }

    public function create(User $user): bool
    {

        return $user->isAdmin() || $user->isPeminjam();
    }

    public function update(User $user, Peminjaman $peminjaman): bool
    {

        return $user->isAdmin();
    }

    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }


    public function approve(User $user, Peminjaman $peminjaman): bool
    {

        return $user->isStaff() && $peminjaman->status === 'menunggu';
    }


    public function reject(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isStaff() && $peminjaman->status === 'menunggu';
    }
}
