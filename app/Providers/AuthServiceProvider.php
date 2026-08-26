<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Administration;
use App\Models\LeavePolicyAssignment;
use App\Policies\LeavePolicyAssignmentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Policies existantes
        \App\Models\LeaveType::class => \App\Policies\LeaveTypePolicy::class,
        \App\Models\LeavePeriod::class => \App\Policies\LeavePeriodPolicy::class,
        \App\Models\CompanyHoliday::class => \App\Policies\CompanyHolidayPolicy::class,
        \App\Models\LeavePolicy::class => \App\Policies\LeavePolicyPolicy::class,
        \App\Models\LeaveWorkflow::class => \App\Policies\LeaveWorkflowPolicy::class,
        
        // ✅ AJOUT : Policy pour LeavePolicyAssignment
        LeavePolicyAssignment::class => LeavePolicyAssignmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); 
        
        // ============================================
        // GATES EXISTANTS
        // ============================================
        
        // Définition du Gate pour les SuperAdmins
        Gate::define('superadmin', function (Administration $user) {
            return $user->isTrueSuperAdmin();
        });
        
        // Définition du Gate pour l'accès au siège
        Gate::define('access-siege', function (Administration $user, $siegeId = null) {
            if ($siegeId === null) {
                return $user->isTrueSuperAdmin() || !empty($user->getSiegeIdsAccessibles());
            }
            return $user->isTrueSuperAdmin() || $user->hasAccessToSiege($siegeId);
        });

        // ============================================
        // ✅ NOUVEAUX GATES POUR LES ASSIGNATIONS
        // ============================================
        
        // ✅ Gate pour vérifier si l'utilisateur est admin
        Gate::define('is-admin', function ($user) {
            if (!$user) return false;
            
            // Super admin
            if (isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1) {
                return true;
            }
            
            // Admin siège (simple admin)
            if (isset($user->is_simple_admin) && $user->is_simple_admin == 1) {
                return true;
            }
            
            // Admin avec droits spécifiques (si la colonne existe)
            if (isset($user->can_manage_leave_policies) && $user->can_manage_leave_policies == 1) {
                return true;
            }
            
            return false;
        });

        // ✅ Gate pour gérer les assignations de politiques
        Gate::define('manage-leave-policy-assignments', function ($user) {
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
        });

        // ✅ Gate pour voir les assignations
        Gate::define('view-leave-policy-assignments', function ($user) {
            if (!$user) return false;
            
            if (isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1) {
                return true;
            }
            
            if (isset($user->is_simple_admin) && $user->is_simple_admin == 1) {
                return true;
            }
            
            return false;
        });

        // ✅ Gate pour créer/modifier/supprimer des assignations
        Gate::define('edit-leave-policy-assignments', function ($user) {
            if (!$user) return false;
            
            if (isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1) {
                return true;
            }
            
            if (isset($user->is_simple_admin) && $user->is_simple_admin == 1) {
                return true;
            }
            
            return false;
        });
    }
}