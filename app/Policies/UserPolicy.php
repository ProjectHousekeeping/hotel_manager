<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isGerente() || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isGerente() && $user->id !== $model->id;
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
