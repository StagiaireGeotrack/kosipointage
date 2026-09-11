<?php
// app/Services/AccessScopeService.php

namespace App\Services;

use App\Models\Administration;
use App\Models\Employe;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class AccessScopeService
{
    /**
     * Retourne la requête Employe filtrée selon le rôle de l'utilisateur connecté
     */
    public static function getAccessibleEmployeeQuery(?Administration $user = null): Builder
    {
        $user = $user ?? auth()->user();

        // Super Admin ou Seller : aucune restriction supplémentaire (SiegeScope gère)
        if ($user->isTrueSuperAdmin() || $user->isSeller()) {
            return Employe::query();
        }

        // Supervisor : filtrer par ses services
        if ($user->isSupervisor()) {
            $serviceIds = $user->getSupervisorServiceIds();
            if (empty($serviceIds)) {
                // Aucun service affecté → aucun employé visible
                return Employe::query()->whereRaw('1 = 0');
            }
            return Employe::query()
                ->where('SiegeID', $user->SiegeID)
                ->whereIn('department_id', $serviceIds);
        }

        // Simple Admin / Manager : comportement actuel (SiegeScope s'applique)
        return Employe::query();
    }

    /**
     * Vérifie qu'un employé est accessible par l'utilisateur
     */
    public static function canAccessEmployee(Administration $user, Employe $employe): bool
    {
        // Vérifier d'abord l'appartenance au siège
        if (!$user->isTrueSuperAdmin() && !$user->isSeller() && $user->SiegeID != $employe->SiegeID) {
            return false;
        }

        // Pour un Supervisor, vérifier aussi le service
        if ($user->isSupervisor()) {
            $serviceIds = $user->getSupervisorServiceIds();
            return in_array($employe->department_id, $serviceIds);
        }

        return true;
    }

    /**
     * Vérifie qu'un service appartient bien au siège donné
     */
    public static function serviceBelongsToSiege(int $serviceId, int $siegeId): bool
    {
        return Department::where('id', $serviceId)
            ->where('site_id', $siegeId)
            ->exists();
    }

    /**
     * Vérifie que TOUS les services appartiennent au siège
     */
    public static function allServicesBelongToSiege(array $serviceIds, int $siegeId): bool
    {
        if (empty($serviceIds)) {
            return false;
        }
        $count = Department::whereIn('id', $serviceIds)
            ->where('site_id', $siegeId)
            ->count();
        return $count === count($serviceIds);
    }
}
