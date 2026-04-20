<?php

namespace App\Policies;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KategoriPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
