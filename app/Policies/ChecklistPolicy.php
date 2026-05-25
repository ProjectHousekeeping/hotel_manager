<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\User;

class ChecklistPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, Checklist $checklist): bool
    {
        return $user->isGerente();
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, Checklist $checklist): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, Checklist $checklist): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
