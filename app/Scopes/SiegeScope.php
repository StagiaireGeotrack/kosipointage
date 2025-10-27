<?php
// app/Scopes/SiegeScope.php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SiegeScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Si l'utilisateur n'est pas connecté, ne pas appliquer de scope
        if (!Auth::check()) {
            return;
        }
        
        $admin = Auth::user();
        
        // Si l'administrateur n'est pas SuperAdmin, limiter aux données de son siège
        if (!$admin->IsSuperAdmin) {
            $table = $model->getTable();
            
            // Vérifier si la table a une colonne SiegeID avant d'appliquer le scope
            if (Schema::hasColumn($table, 'SiegeID')) {
                $builder->where($table . '.SiegeID', $admin->SiegeID);
            }
            // Si c'est la table Entreprises_sieges, filtrer par ID
            elseif ($table === 'Entreprises_sieges') {
                $builder->where($table . '.ID', $admin->SiegeID);
            }
        }
    }
}