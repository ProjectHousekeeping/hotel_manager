<?php

namespace App\Policies;

use App\Models\TipoUrgencia;
use App\Models\User;

class TipoUrgenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, TipoUrgencia $tipoUrgencia): bool
    {
        return $user->isGerente();
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, TipoUrgencia $tipoUrgencia): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, TipoUrgencia $tipoUrgencia): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
