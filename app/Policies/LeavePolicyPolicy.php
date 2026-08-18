<?php
// app/Policies/LeavePolicyPolicy.php

namespace App\Policies;

use App\Models\LeavePolicy;

class LeavePolicyPolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, LeavePolicy $leavePolicy): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $leavePolicy->isGlobal() || $leavePolicy->site_id == $user->SiegeID;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, LeavePolicy $leavePolicy): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Politique propre au siège
        if (! $leavePolicy->isGlobal() && $leavePolicy->site_id == $user->SiegeID) {
            return true;
        }

        // Global customizable → override autorisé
        if ($leavePolicy->isGlobal() && $leavePolicy->is_customizable) {
            return true;
        }

        return false;
    }

    public function delete($user, LeavePolicy $leavePolicy): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Un admin site ne peut supprimer que ses politiques propres.
        return ! $leavePolicy->isGlobal() && $leavePolicy->site_id == $user->SiegeID;
    }
}