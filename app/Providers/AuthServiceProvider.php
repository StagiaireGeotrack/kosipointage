<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Administration;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];
    
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); 
        
        // Définition du Gate pour les SuperAdmins (vrais Super Admin uniquement, pas les vendeurs)
        Gate::define('superadmin', function (Administration $user) {
            return $user->isTrueSuperAdmin();
        });
        
        // Définition du Gate pour l'accès au siège
        Gate::define('access-siege', function (Administration $user, $siegeId) {
            return $user->isTrueSuperAdmin() || $user->hasAccessToSiege($siegeId);
        });
    }
}