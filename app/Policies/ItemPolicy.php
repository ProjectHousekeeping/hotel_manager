<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function view(User $user, Item $item): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function create(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function update(User $user, Item $item): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function delete(User $user, Item $item): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
