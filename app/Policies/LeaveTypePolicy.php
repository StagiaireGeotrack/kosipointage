<?php

namespace App\Policies;

use App\Models\LeaveType;

class LeaveTypePolicy
{
    /**
     * Détermine si l'utilisateur est superadmin.
     */
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
        return ! $leaveType->isGlobal() && $leaveType->site_id == $user->SiegeID;
    }

    public function delete($user, LeaveType $leaveType): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return ! $leaveType->isGlobal() && $leaveType->site_id == $user->SiegeID;
    }
}