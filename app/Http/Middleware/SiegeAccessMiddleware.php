<?php
// app/Http/Middleware/SiegeAccessMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SiegeAccessMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // ✅ Super Admin : passage libre
        if ($user->isTrueSuperAdmin()) {
            return $next($request);
        }

        // ✅ Revendeur : passage libre (SiegeScope filtre les données)
        if ($user->isSeller()) {
            return $next($request);
        }

        // ✅ Responsable de service : vérifier l'accès à SON siège
        if ($user->isSupervisor()) {
            if (!$user->SiegeID || !Gate::allows('access-siege', $user->SiegeID)) {
                abort(403, 'Accès non autorisé à ce siège.');
            }

            return $next($request);
        }

        // ✅ Simple Admin / Master : vérifier l'accès au siège
                // ✅ Pour les Simple Administrateurs ET Supervisors, vérifier l'accès au siège
        if ($user->isSimpleAdmin() || $user->isSupervisor()) {
            $siegeId = $request->route('siege_id') ?? $user->SiegeID;
            
            if (!$siegeId || !Gate::allows('access-siege', $siegeId)) {
                abort(403, 'Accès non autorisé à ce siège.');
            }
            
            return $next($request);
        }

        // Aucun rôle reconnu
        abort(403, 'Accès non autorisé.');
    }
}