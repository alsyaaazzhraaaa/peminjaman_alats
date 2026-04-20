<?php

namespace App\Policies;

use App\Models\Alat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlatPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPeminjam();
    }

    public function view(User $user, Alat $alat): bool
    {

        return $user->isAdmin() || $user->isPeminjam() || $user->isPetugas();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
