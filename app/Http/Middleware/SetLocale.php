<?php
// app/Http/Middleware/SetLocale.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si une langue est stockée en session, l'utiliser
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } 
        // Sinon, si l'utilisateur est connecté et a une préférence de langue stockée
        // elseif (auth()->check() && auth()->user()->locale) {
        //     App::setLocale(auth()->user()->locale);
        //     Session::put('locale', auth()->user()->locale);
        // }
        
        return $next($request);
    }
}