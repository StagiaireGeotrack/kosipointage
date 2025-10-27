<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Administration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'Identifiant_email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        
        // Rechercher manuellement l'utilisateur
        $admin = Administration::where('Identifiant_email', $credentials['Identifiant_email'])->first();
        
        if ($admin && $admin->Password_ === sha1($credentials['password'])) {
            Auth::login($admin, $request->boolean('remember'));
            
            $request->session()->regenerate();
            
            if ($admin->IsSuperAdmin) {
                return redirect()->intended(route('dashboard'));
            } else {
                // Rediriger les admin standards vers une autre page
                // Par exemple la liste des entreprises de leur siège
                return redirect()->intended(route('entreprises.index'));
            }
        }
        
        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
