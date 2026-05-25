<?php

namespace App\Policies;

use App\Models\Cargo;
use App\Models\User;

class CargoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, Cargo $cargo): bool
    {
        return $user->isGerente();
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, Cargo $cargo): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, Cargo $cargo): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
