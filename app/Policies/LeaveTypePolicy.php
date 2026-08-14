<?php
// app/Policies/LeaveTypePolicy.php

namespace App\Policies;

use App\Models\LeaveType;

class LeaveTypePolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, LeaveType $leaveType): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $leaveType->isGlobal() || $leaveType->site_id == $user->SiegeID;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, LeaveType $leaveType): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Type propre au siège
        if (! $leaveType->isGlobal() && $leaveType->site_id == $user->SiegeID) {
            return true;
        }

        // Global customizable → override autorisé
        if ($leaveType->isGlobal() && $leaveType->is_customizable) {
            return true;
        }

        return false;
    }

    public function delete($user, LeaveType $leaveType): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Un admin site ne peut supprimer que ses types propres.
        // Jamais un global (même customizable).
        return ! $leaveType->isGlobal() && $leaveType->site_id == $user->SiegeID;
    }
}