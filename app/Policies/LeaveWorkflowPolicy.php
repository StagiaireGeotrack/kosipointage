<?php
// app/Policies/LeaveWorkflowPolicy.php

namespace App\Policies;

use App\Models\LeaveWorkflow;

class LeaveWorkflowPolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, LeaveWorkflow $leaveWorkflow): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $leaveWorkflow->isGlobal() || $leaveWorkflow->site_id == $user->SiegeID;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, LeaveWorkflow $leaveWorkflow): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Workflow propre au siège
        if (! $leaveWorkflow->isGlobal() && $leaveWorkflow->site_id == $user->SiegeID) {
            return true;
        }

        // Global customizable → override autorisé
        if ($leaveWorkflow->isGlobal() && $leaveWorkflow->is_customizable) {
            return true;
        }

        return false;
    }

    public function delete($user, LeaveWorkflow $leaveWorkflow): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Un admin site ne peut supprimer que ses workflows propres.
        return ! $leaveWorkflow->isGlobal() && $leaveWorkflow->site_id == $user->SiegeID;
    }
}