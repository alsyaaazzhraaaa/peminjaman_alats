<?php

namespace App\Policies;

use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengembalianPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

        return true;
    }

    public function view(User $user, Pengembalian $pengembalian): bool
    {
        if ($user->isStaff()) {
            return true;
        }

        return $user->id === $pengembalian->peminjaman->id_user;
    }

    public function create(User $user): bool
    {

        return $user->isAdmin() || $user->isPeminjam();
    }

    public function update(User $user, Pengembalian $pengembalian): bool
    {

        return $user->isAdmin();
    }

    public function delete(User $user, Pengembalian $pengembalian): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
