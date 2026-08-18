<?php
// app/Policies/LeavePolicyAssignmentPolicy.php

namespace App\Policies;

use App\Models\LeavePolicyAssignment;

class LeavePolicyAssignmentPolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, LeavePolicyAssignment $assignment): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $assignment->site_id == $user->SiegeID || $assignment->site_id === null;
    }

    public function create($user): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function update($user, LeavePolicyAssignment $assignment): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $assignment->site_id == $user->SiegeID;
    }

    public function delete($user, LeavePolicyAssignment $assignment): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $assignment->site_id == $user->SiegeID;
    }
}