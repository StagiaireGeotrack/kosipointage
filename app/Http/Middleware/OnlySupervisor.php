<?php
// app/Http/Middleware/OnlySupervisor.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlySupervisor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !method_exists($user, 'isSupervisor') || !$user->isSupervisor()) {
            abort(403, __('Cette section est réservée aux Responsables de service.'));
        }

        return $next($request);
    }
}
