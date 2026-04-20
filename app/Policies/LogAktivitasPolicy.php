<?php

namespace App\Policies;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogAktivitasPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

        return $user->isAdmin();
    }

    public function view(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, LogAktivitas $logAktivitas): bool
    {
        return false;
    }

    public function delete(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
