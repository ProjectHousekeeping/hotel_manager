<?php

namespace App\Policies;

use App\Models\ItemChecklist;
use App\Models\User;

class ItemChecklistPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerente();
    }

    public function view(User $user, ItemChecklist $itemChecklist): bool
    {
        return $user->isGerente();
    }

    public function create(User $user): bool
    {
        return $user->isGerente();
    }

    public function update(User $user, ItemChecklist $itemChecklist): bool
    {
        return $user->isGerente();
    }

    public function delete(User $user, ItemChecklist $itemChecklist): bool
    {
        return $user->isGerente();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isGerente();
    }
}
