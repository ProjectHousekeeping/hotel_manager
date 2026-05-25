<?php

namespace App\Policies;

use App\Models\TipoTarefa;
use App\Models\User;

class TipoTarefaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, TipoTarefa $tipoTarefa): bool
    {
        return $user->isGerente();
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, TipoTarefa $tipoTarefa): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, TipoTarefa $tipoTarefa): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
