<?php
// app/Policies/CompanyHolidayPolicy.php

namespace App\Policies;

use App\Models\CompanyHoliday;

class CompanyHolidayPolicy
{
    private function isSuperAdmin($user): bool
    {
        return $user && $user->IsSuperAdmin == 1;
    }

    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, CompanyHoliday $companyHoliday): bool
    {
        if ($this->isSuperAdmin($user)) return true;
        return $companyHoliday->isGlobal() || $companyHoliday->site_id == $user->SiegeID;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, CompanyHoliday $companyHoliday): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Jour férié propre au siège
        if (! $companyHoliday->isGlobal() && $companyHoliday->site_id == $user->SiegeID) {
            return true;
        }

        // Global customizable → override autorisé
        if ($companyHoliday->isGlobal() && $companyHoliday->is_customizable) {
            return true;
        }

        return false;
    }

    public function delete($user, CompanyHoliday $companyHoliday): bool
    {
        if ($this->isSuperAdmin($user)) return true;

        // Un admin site ne peut supprimer que ses jours fériés propres.
        return ! $companyHoliday->isGlobal() && $companyHoliday->site_id == $user->SiegeID;
    }
}