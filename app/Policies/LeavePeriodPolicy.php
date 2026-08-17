<?php
// app/Policies/LeavePeriodPolicy.php

namespace App\Policies;

use App\Models\LeavePeriod;

class LeavePeriodPolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, LeavePeriod $leavePeriod): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $leavePeriod->isGlobal() || $leavePeriod->site_id == $user->SiegeID;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, LeavePeriod $leavePeriod): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Période propre au siège
        if (! $leavePeriod->isGlobal() && $leavePeriod->site_id == $user->SiegeID) {
            return true;
        }

        // Global customizable → override autorisé
        if ($leavePeriod->isGlobal() && $leavePeriod->is_customizable) {
            return true;
        }

        return false;
    }

    public function delete($user, LeavePeriod $leavePeriod): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Un admin site ne peut supprimer que ses périodes propres.
        // Jamais une période globale (même customizable).
        return ! $leavePeriod->isGlobal() && $leavePeriod->site_id == $user->SiegeID;
    }
}