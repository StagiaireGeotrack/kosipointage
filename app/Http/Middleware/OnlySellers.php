<?php
// app/Http/Middleware/OnlySellers.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlySellers
{
    /**
     * Handle an incoming request.
     * Laisse passer UNIQUEMENT les vendeurs (bloque Super Admin et Simple Admin)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est un vendeur, le laisser passer
        if ($user && $user->isSeller()) {
            return $next($request);
        }

        // Sinon, rediriger ou bloquer
        abort(403, __('Cette section est réservée aux vendeurs uniquement.'));
    }
}