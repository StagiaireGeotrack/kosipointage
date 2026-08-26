<?php
// app/Policies/LeavePolicyAssignmentPolicy.php

namespace App\Policies;

use App\Models\Administration;
use App\Models\LeavePolicyAssignment;
use Illuminate\Auth\Access\Response;

class LeavePolicyAssignmentPolicy
{
    /**
     * Vérifier si l'utilisateur est admin (super admin ou simple admin)
     */
    private function isAdmin($user): bool
    {
        if (!$user) return false;
        
        // Super admin
        if (isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1) {
            return true;
        }
        
        // Admin siège (simple admin)
        if (isset($user->is_simple_admin) && $user->is_simple_admin == 1) {
            return true;
        }
        
        return false;
    }

    /**
     * Vérifier si l'utilisateur peut voir la liste des assignations
     */
    public function viewAny(Administration $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut voir une assignation
     */
    public function view(Administration $user, LeavePolicyAssignment $leavePolicyAssignment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut créer une assignation
     */
    public function create(Administration $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut modifier une assignation
     */
    public function update(Administration $user, LeavePolicyAssignment $leavePolicyAssignment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut supprimer une assignation
     */
    public function delete(Administration $user, LeavePolicyAssignment $leavePolicyAssignment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut restaurer une assignation
     */
    public function restore(Administration $user, LeavePolicyAssignment $leavePolicyAssignment): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Vérifier si l'utilisateur peut supprimer définitivement une assignation
     */
    public function forceDelete(Administration $user, LeavePolicyAssignment $leavePolicyAssignment): bool
    {
        return $this->isAdmin($user);
    }
}