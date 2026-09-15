<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Administration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogService;

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
            ActivityLogService::log(
                action: 'login_failed',
                description: 'Identifiant introuvable',
                userEmail: $credentials['Identifiant_email'],
            );
            return back()->withErrors([
                'email' => __('Cet identifiant ou e-mail est introuvable.'),
            ])->onlyInput('email');
        }

        if (isset($admin->Actived) && !$admin->Actived) {
            ActivityLogService::log(
                action: 'login_failed',
                description: 'Compte désactivé',
                userEmail: $credentials['Identifiant_email'],
            );
            return back()->withErrors([
                'actived' => __('Votre compte administrateur est désactivé.'),
            ])->onlyInput('email');
        }

        if ($admin->Password_ !== sha1($credentials['password'])) {
            ActivityLogService::log(
                action: 'login_failed',
                description: 'Mot de passe incorrect',
                userEmail: $credentials['Identifiant_email'],
            );
            return back()->withErrors([
                'password' => __('Le mot de passe saisi est incorrect.'),
            ])->onlyInput('email');
        }

        Auth::login($admin, $request->boolean('remember'));
        $request->session()->regenerate();

                // ✅ Ordre des tests IMPORTANT : du plus spécifique au plus général
        if ($admin->isTrueSuperAdmin()) {
            return redirect()->intended(route('dashboard'));
        }
        elseif ($admin->isSeller()) {
            return redirect()->intended(route('dashboard.seller'));
        }
        elseif ($admin->isSimpleAdmin()) {
            // Simple Admin PUR ou Master
            return redirect()->intended(route('dashboard.simple-admin'));
        }
        elseif ($admin->isSupervisor()) {
            // ✅ Supervisor utilise le MÊME dashboard que le Simple Admin
            return redirect()->intended(route('dashboard.simple-admin'));
        }

        // Fallback (ne devrait jamais arriver)
        return redirect()->intended(route('sieges.index'));
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
