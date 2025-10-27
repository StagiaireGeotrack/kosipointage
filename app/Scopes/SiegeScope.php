<?php
// app/Scopes/SiegeScope.php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

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
             $builder->where('SiegeID', Auth::user()->SiegeID);
        }
    }
}