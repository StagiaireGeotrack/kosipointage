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
        
        // Si l'utilisateur est SuperAdmin, on le laisse passer
        if (Gate::allows('superadmin')) {
            return $next($request);
        }
        
        // Pour les administrateurs réguliers, vérifier l'accès au SiegeID
        $siegeId = $request->route('siege_id') ?? Auth::user()->SiegeID;
        
        if (!Gate::allows('access-siege', $siegeId)) {
            abort(403, 'Accès non autorisé à ce siège.');
        }
        
        return $next($request);
    }
}