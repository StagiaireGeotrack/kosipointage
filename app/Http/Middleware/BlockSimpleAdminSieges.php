<?php
// app/Http/Middleware/BlockSimpleAdminSieges.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSimpleAdminSieges
{
    /**
     * Handle an incoming request.
     * Bloque l'accès aux Simple Admin pour la gestion des sièges
     * Seul le vrai Super Admin peut gérer les sièges
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est un Simple Admin, bloquer l'accès
        if ($user && $user->isSimpleAdmin()) {
            abort(403, __('Vous n\'avez pas l\'autorisation de gérer les sièges.'));
        }

        return $next($request);
    }
}