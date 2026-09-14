<?php
// app/Services/AccessScopeService.php

namespace App\Services;

use App\Models\Administration;
use App\Models\Employe;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class AccessScopeService
{
    public static function getAccessibleEmployeeQuery(?Administration $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if ($user->isTrueSuperAdmin() || $user->isSeller()) {
            return Employe::query();
        }

        if ($user->isSupervisor()) {
            $serviceIds = $user->getSupervisorServiceIds();
            if (empty($serviceIds)) {
                return Employe::query()->whereRaw('1 = 0');
            }
            return Employe::query()
                ->where('SiegeID', $user->SiegeID)
                ->whereIn('department_id', $serviceIds);
        }

        return Employe::query();
    }

    /**
     * IDs des employés accessibles au compte connecté.
     * Utilisé pour filtrer Pointages / Congés / etc.
     */
    public static function getAccessibleEmployeeIds(?Administration $user = null): array
    {
        return self::getAccessibleEmployeeQuery($user)->pluck('ID')->toArray();
    }

    /**
     * IDs des services accessibles au compte connecté.
     * Retourne null si l'utilisateur voit tous les services (SuperAdmin, Seller, Simple Admin).
     */
    public static function getAccessibleServiceIds(?Administration $user = null): ?array
    {
        $user = $user ?? auth()->user();

        if ($user->isSupervisor()) {
            return $user->getSupervisorServiceIds();
        }

        return null; // null = tous les services du siège
    }

    public static function canAccessEmployee(Administration $user, Employe $employe): bool
    {
        if (!$user->isTrueSuperAdmin() && !$user->isSeller() && $user->SiegeID != $employe->SiegeID) {
            return false;
        }

        if ($user->isSupervisor()) {
            return in_array($employe->department_id, $user->getSupervisorServiceIds(), true);
        }

        return true;
    }
}