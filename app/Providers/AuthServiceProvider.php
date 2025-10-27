<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Administration;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];
    
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Définition du Gate pour les SuperAdmins
        Gate::define('superadmin', function (Administration $user) {
            return $user->IsSuperAdmin;
        });
        
        // Définition du Gate pour l'accès au siège
        Gate::define('access-siege', function (Administration $user, $siegeId) {
            return $user->IsSuperAdmin || $user->SiegeID == $siegeId;
        });
    }
}