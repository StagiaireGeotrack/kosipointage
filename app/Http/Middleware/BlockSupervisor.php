<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockSupervisor
{
    /**
     * Préfixes de noms de routes INTERDITES au Responsable de service.
     * ⚠️ Ne PAS mettre 'dashboard' seul ici : cela bloquerait aussi dashboard.simple-admin
     */
    protected array $forbiddenPrefixes = [
        // Gestion plateforme
        'sieges.',
        'entreprises.',
        'administrateurs.',
        'sellers.',
        'supervisors.',
        'activity-logs.',
        'impersonate',

        // Dashboards interdits (SuperAdmin et Seller uniquement)
        'dashboard.seller',
        // 'dashboard' → géré par la vérification exacte dans handle()
        // 'dashboard.simple-admin' → ✅ AUTORISÉ pour le Supervisor

        // Admin / Organisation
        'admin.',

        // Planning : écriture + paramètres
        'planning.create',
        'planning.store',
        'planning.edit',
        'planning.update',
        'planning.destroy',
        'planning.horaires-types.',
        'planning.events.store',
        'planning.export.',

        // Employés : écriture + exports
        'employes.create',
        'employes.store',
        'employes.edit',
        'employes.update',
        'employes.destroy',
        'employes.reset',
        'employes.reset-pin',
        'employes.assign-web-access',
        'employes.export.',

        // Pointages : écriture + exports
        'pointages.create',
        'pointages.store',
        'pointages.edit',
        'pointages.update',
        'pointages.destroy',
        'pointages.export.',

        // Autres modules interdits
        'jours-non-travailles.',
        'evenements.',
        'leave-balances.',
        'conges.create',
        'conges.store',
        'conges.edit',
        'conges.update',
        'conges.destroy',
        'conges.export.',
        // Rapports : exports interdits
         'reports.export.',
        'reports.rapport-auto', 
    ];

    /**
     * Liste EXACTE de noms de routes interdites (comparaison stricte).
     * Utilisé pour bloquer précisément une route sans bloquer ses sous-routes.
     */
    protected array $forbiddenExactRoutes = [
        'dashboard',          // Dashboard SuperAdmin
        // 'dashboard.simple-admin' → ✅ AUTORISÉ
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // On ne bloque QUE les Supervisors (aucun impact sur les autres rôles)
        if ($user && method_exists($user, 'isSupervisor') && $user->isSupervisor()) {

            $routeName = $request->route() ? $request->route()->getName() : null;

            if ($routeName) {

                // 1) Vérification EXACTE (pour bloquer 'dashboard' sans bloquer 'dashboard.simple-admin')
                if (in_array($routeName, $this->forbiddenExactRoutes, true)) {
                    abort(403, 'Accès non autorisé pour un Responsable de service.');
                }

                // 2) Vérification par PRÉFIXE
                foreach ($this->forbiddenPrefixes as $prefix) {
                    if (str_starts_with($routeName, $prefix)) {
                        abort(403, 'Accès non autorisé pour un Responsable de service.');
                    }
                }
            }
        }

        return $next($request);
    }
}