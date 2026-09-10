<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockSupervisor
{
    /**
     * Préfixes de noms de routes INTERDITES au Responsable de service.
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
        'dashboard',
        'dashboard.seller',
        'dashboard.simple-admin',

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
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // On ne bloque QUE les Supervisors
        if ($user && method_exists($user, 'isSupervisor') && $user->isSupervisor()) {

            $routeName = $request->route() ? $request->route()->getName() : null;

            if ($routeName) {
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