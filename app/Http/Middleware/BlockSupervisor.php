<?php
// app/Http/Middleware/BlockSupervisor.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSupervisor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            abort(403, __('Cette action n\'est pas autorisée pour un Responsable de service.'));
        }

        return $next($request);
    }
}
