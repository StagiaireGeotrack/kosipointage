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
        
        // ✅ Si c'est un vrai Super Admin, on le laisse passer
        if ($user->isTrueSuperAdmin()) {
            return $next($request);
        }
        
        // ✅ Si c'est un Vendeur, on le laisse passer (le SiegeScope filtrera les données)
        if ($user->isSeller()) {
            return $next($request);
        }
        
        // ✅ Pour les Simple Administrateurs, vérifier l'accès au siège
        if ($user->isSimpleAdmin()) {
            $siegeId = $request->route('siege_id') ?? $user->SiegeID;
            
            if (!$siegeId || !Gate::allows('access-siege', $siegeId)) {
                abort(403, 'Accès non autorisé à ce siège.');
            }
            
            return $next($request);
        }
        
        // Si aucun des cas ci-dessus, bloquer l'accès
        abort(403, 'Accès non autorisé.');
    }
}