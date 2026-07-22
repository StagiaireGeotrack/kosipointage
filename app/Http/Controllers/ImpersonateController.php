<?php

namespace App\Http\Controllers;

use App\Models\Administration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Obtenir la route du dashboard selon le type d'utilisateur
     */
    private function getDashboardRoute($user)
    {
        if ($user->isSeller()) {
            return 'dashboard.seller';
        } elseif ($user->isSimpleAdmin()) {
            return 'dashboard.simple-admin';
        }
        return 'dashboard';
    }

    /**
     * Démarrer l'impersonation (connexion en tant que)
     */
    public function impersonate(Request $request, $id)
    {
        $impersonator = Auth::user();

        // Sécurité : Seul un vrai Super Admin peut initier l'impersonation
        if (!$impersonator || !$impersonator->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Accès non autorisé.'));
        }

        // Empêcher de s'impersonifier soi-même
        if ($impersonator->ID == $id) {
            return redirect()->back()->with('error', __('Vous ne pouvez pas vous connecter en tant que vous-même.'));
        }

        // Trouver l'utilisateur cible
        $targetUser = Administration::findOrFail($id);

        // Sauvegarder l'ID de l'impersonateur dans la session
        session(['impersonator_id' => $impersonator->ID]);
        
        // Se connecter en tant que l'utilisateur cible
        Auth::login($targetUser);

        // Rediriger vers le dashboard approprié
        return redirect()->route($this->getDashboardRoute($targetUser))
            ->with('success', __('Vous êtes maintenant connecté en tant que ' . $targetUser->Identifiant_email));
    }

    /**
     * Quitter l'impersonation et retourner au compte original
     */
    public function leave(Request $request)
    {
        $currentUser = Auth::user();

        // Vérifier si on est en train d'impersonifier quelqu'un
        if (!session()->has('impersonator_id')) {
            return redirect()->route($currentUser ? $this->getDashboardRoute($currentUser) : 'login');
        }

        $impersonatorId = session('impersonator_id');
        
        // Trouver le super admin original
        $impersonator = Administration::find($impersonatorId);

        if (!$impersonator) {
            session()->forget('impersonator_id');
            Auth::logout();
            return redirect()->route('login')->with('error', __('Compte original introuvable.'));
        }

        // Nettoyer la session
        session()->forget('impersonator_id');

        // Se reconnecter au compte original
        Auth::login($impersonator);

        return redirect()->route($this->getDashboardRoute($impersonator))
            ->with('success', __('Vous êtes de retour sur votre compte.'));
    }

    /**
     * Démarrer l'impersonation d'un employé
     */
    public function impersonateEmploye(Request $request, $id)
    {
        $impersonator = Auth::guard('web')->user();

        // Sécurité : Seul un vrai Super Admin peut initier l'impersonation
        if (!$impersonator || !$impersonator->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Accès non autorisé.'));
        }

        $targetUser = \App\Models\Employe::findOrFail($id);

        // Sauvegarder l'ID de l'impersonateur
        session(['impersonator_admin_id' => $impersonator->ID]);
        
        // Se connecter en tant que l'employé
        Auth::guard('employe')->login($targetUser);

        return redirect()->route('employe.dashboard')
            ->with('success', __('Vous êtes maintenant connecté en tant que ' . $targetUser->Nom));
    }

    /**
     * Quitter l'impersonation d'un employé
     */
    public function leaveEmploye(Request $request)
    {
        session()->forget('impersonator_admin_id');
        Auth::guard('employe')->logout();

        return redirect()->route('employes.index')
            ->with('success', __('Vous avez quitté l\'espace employé.'));
    }
}
