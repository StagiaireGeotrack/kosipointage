<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Administration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

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
        
        $admin = Administration::where('Identifiant_email', $credentials['Identifiant_email'])->first();
        
        if (!$admin) {
            return back()->withErrors([
                'email' => __('Cet identifiant ou e-mail est introuvable.'),
            ])->onlyInput('email');
        }

        if (isset($admin->Actived) && !$admin->Actived) {
            return back()->withErrors([
                'actived' => __('Votre compte administrateur est désactivé.'),
            ])->onlyInput('email');
        }

        if ($admin->Password_ !== sha1($credentials['password'])) {
            return back()->withErrors([
                'password' => __('Le mot de passe saisi est incorrect.'),
            ])->onlyInput('email');
        }

        Auth::login($admin, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($admin->isTrueSuperAdmin() ) 
        {
            return redirect()->intended(route('dashboard'));
        } 
        elseif($admin->isSimpleAdmin() ) 
        {        
            return redirect()->intended(route('dashboard.simple-admin'));
        }
        else
        {
            return redirect()->intended(route('dashboard.seller'));
        }
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
