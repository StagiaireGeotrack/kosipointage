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
        
        // Si l'utilisateur est un employé (connecté via le portail)
        if ($admin instanceof \App\Models\Employe) {
            $table = $model->getTable();
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'SiegeID')) {
                $builder->where($table . '.SiegeID', $admin->SiegeID);
            }
            return;
        }
        
        // Si c'est un vrai Super Admin (pas un vendeur), pas de restriction
        if ($admin->isTrueSuperAdmin()) {
            return;
        }
        
        $table = $model->getTable();
        
        // Gestion pour les vendeurs
        if ($admin->isSeller()) {
            $siegeIds = $admin->getSiegeIdsAccessibles();
            
            // Si le vendeur n'a aucun siège assigné, bloquer l'accès
            if (empty($siegeIds)) {
                $builder->whereRaw('1 = 0');
                return;
            }
            
            // Vérifier si la table a une colonne SiegeID
            if (Schema::hasColumn($table, 'SiegeID')) {
                $builder->whereIn($table . '.SiegeID', $siegeIds);
            }
            // Si c'est la table Entreprises_sieges, filtrer par ID
            elseif ($table === 'Entreprises_sieges') {
                $builder->whereIn($table . '.ID', $siegeIds);
            }
            
            return;
        }
        
        // Gestion pour les Simple Admin
        if ($admin->isSimpleAdmin()) {
            // Si le Simple Admin n'a pas de siège assigné, bloquer l'accès
            if (!$admin->SiegeID) {
                $builder->whereRaw('1 = 0');
                return;
            }
            
            // Vérifier si la table a une colonne SiegeID
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