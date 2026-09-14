<?php
// app/Http/Middleware/EnsureAccountActive.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Vérifier que le compte est actif et non supprimé
            $actived = $user->Actived ?? 1;
            $deleted = $user->deleted ?? 0;

            if ($actived == 0 || $deleted == 1) {
                Auth::guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', __('Votre compte a été désactivé.'));
            }
        }

        return $next($request);
    }
}
