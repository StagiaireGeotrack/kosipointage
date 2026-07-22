<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Employe;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\ActivityLogService;

class EmployeAuthController extends Controller
{
    /**
     * Display the employee login view.
     */
    public function create(): View
    {
        return view('auth.employe-login');
    }

    /**
     * Handle an incoming authentication request for employees.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $credentials = $request->only('email', 'password');
        
        $employe = Employe::where('email', $credentials['email'])->first();
        
        if (!$employe) {
            return back()->withErrors([
                'email' => __('Cet identifiant ou e-mail est introuvable dans notre système.'),
            ])->onlyInput('email');
        }

        if ($employe->deleted) {
            return back()->withErrors([
                'account_error' => __('Ce compte a été supprimé. Veuillez contacter votre administrateur.'),
            ])->onlyInput('email');
        }

        if (isset($employe->Actived) && !$employe->Actived) {
            return back()->withErrors([
                'account_error' => __('Votre compte employé est désactivé. Veuillez contacter votre administrateur.'),
            ])->onlyInput('email');
        }

        // Try to authenticate with the employe guard
        if (Auth::guard('employe')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect to employee dashboard/home
            return redirect()->intended(route('employe.dashboard'));
        }

        return back()->withErrors([
            'password' => __('Le mot de passe saisi est incorrect.'),
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('employe')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employe.login');
    }
}
