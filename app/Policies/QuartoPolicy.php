<?php

namespace App\Policies;

use App\Models\Quarto;
use App\Models\User;

class QuartoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista() || $user->isOperacional();
    }

    public function view(User $user, Quarto $quarto): bool
    {
        if ($user->isGerente() || $user->isRecepcionista()) {
            return true;
        }

        if ($user->isOperacional()) {
            return $quarto->tarefas()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function update(User $user, Quarto $quarto): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function delete(User $user, Quarto $quarto): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function restore(User $user, Quarto $quarto): bool
    {
        return $user->isGerente();
    }

    public function forceDelete(User $user, Quarto $quarto): bool
    {
        return $user->isGerente();
    }
}
