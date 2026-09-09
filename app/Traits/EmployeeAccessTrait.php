<?php

namespace App\Traits;

use App\Models\Employe;
use App\Models\SellerSieges;
use App\Models\SupervisorServices;

trait EmployeeAccessTrait
{
    /**
     * Retourne la liste des IDs des employés accessibles par l'utilisateur connecté.
     */
    public function getAccessibleEmployeeIds($user)
    {
        // 1. SuperAdmin (vrai) : tout voir
        if ($user->isTrueSuperAdmin()) {
            return Employe::pluck('ID')->toArray();
        }

        // 2. Seller : voir les employés des sièges qui lui sont assignés
        if ($user->isSeller()) {
            $siegeIds = $user->sellerSieges()->pluck('Entreprises_sieges.ID')->toArray();
            return Employe::whereIn('SiegeID', $siegeIds)->pluck('ID')->toArray();
        }

        // 3. Manager (simple admin) : voir tous les employés de son propre siège
        if ($user->isManagerSimpleAdmin() || ($user->isSimpleAdmin() && $user->IsManager == 1)) {
            return Employe::where('SiegeID', $user->SiegeID)->pluck('ID')->toArray();
        }

        // 4. SUPERVISEUR (le nouveau) : voir les employés de son siège ET de ses services
        if ($user->isSupervisor()) {
            // Récupère les IDs des services supervisés
            $serviceIds = $user->supervisorServices()->pluck('departments.id')->toArray();
            
            return Employe::where('SiegeID', $user->SiegeID)
                          ->whereIn('department_id', $serviceIds)
                          ->pluck('ID')
                          ->toArray();
        }

        // Si aucun rôle correspondant, ne voir aucun employé
        return [];
    }
}