<?php
// app/Http/Middleware/BlockSellers.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSellers
{
    /**
     * Handle an incoming request.
     * Bloque l'accès aux vendeurs pour certaines routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est un vendeur, bloquer l'accès
        if ($user && $user->isSeller()) {
            abort(403, __('Les revendeurs n\'ont pas accès à cette section.'));
        }

        return $next($request);
    }
}