<?php

namespace App\Policies;

use App\Models\Tarefa;
use App\Models\User;

class TarefaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente()
            || $user->isRecepcionista()
            || $user->isOperacional();
    }

    public function view(User $user, Tarefa $tarefa): bool
    {
        if ($user->isGerente() || $user->isRecepcionista()) {
            return true;
        }

        return $user->isOperacional() && $tarefa->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function update(User $user, Tarefa $tarefa): bool
    {
        if ($user->isGerente() || $user->isRecepcionista()) {
            return true;
        }

        return $user->isOperacional() && $tarefa->user_id === $user->id;
    }

    public function delete(User $user, Tarefa $tarefa): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente() || $user->isRecepcionista();
    }
}
