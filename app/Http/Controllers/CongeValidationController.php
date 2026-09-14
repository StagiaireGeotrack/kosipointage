<?php
// app/Http/Controllers/CongeValidationController.php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use App\Models\CongeValidation;
use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;

class CongeValidationController extends Controller
{
public function index(Request $request)
{
    $user = auth()->user();

    // ============================================================
    // ✅ BLOQUER le Supervisor : interdiction de valider les congés
    // (cahier des charges §14 : le Supervisor ne valide PAS les congés)
    // ============================================================
    if ($user->isSupervisor()) {
        abort(403, __('Vous n\'avez pas l\'autorisation de valider les congés.'));
    }

    $isSuperAdmin = $user->isTrueSuperAdmin();
    $siegeId      = $user->SiegeID;

    $filters = $request->only(['status', 'search', 'date_from', 'date_to', 'siege_id']);

    $query = CongeValidation::with('siege');

    if ($isSuperAdmin) {
        if (!empty($filters['siege_id'])) {
            $query->where('SiegeID', $filters['siege_id']);
        }
    } else {
        $query->where('SiegeID', $siegeId);
    }

    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    if (!empty($filters['date_from'])) {
        $query->whereDate('date_heure_debut', '>=', $filters['date_from']);
    }

    if (!empty($filters['date_to'])) {
        $query->whereDate('date_heure_fin', '<=', $filters['date_to']);
    }

    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->where('nom_prenom', 'like', "%{$search}%")
              ->orWhere('email',     'like', "%{$search}%")
              ->orWhere('matricule', 'like', "%{$search}%");
        });
    }

    $validations = $query
        ->orderByRaw("FIELD(status, 'en_cours', 'validated', 'not_validated')")
        ->orderBy('date_creation', 'desc')
        ->paginate(15)
        ->appends($filters);

    $baseCount = $isSuperAdmin
        ? CongeValidation::query()
        : CongeValidation::where('SiegeID', $siegeId);

    $counts = [
        'all'           => (clone $baseCount)->count(),
        'en_cours'      => (clone $baseCount)->where('status', 'en_cours')->count(),
        'validated'     => (clone $baseCount)->where('status', 'validated')->count(),
        'not_validated' => (clone $baseCount)->where('status', 'not_validated')->count(),
    ];

    $sieges   = $isSuperAdmin ? EntrepriseSiege::orderBy('Nom')->get() : collect();
    $employes = Employe::orderBy('Nom')->get();

    return view('conge-validations.index', compact(
        'validations', 'filters', 'counts', 'sieges', 'isSuperAdmin', 'employes'
    ));
}

    // ─────────────────────────────────────────────────────────────────
    // Valider une demande → crée un Conge + met à jour le statut
    // ─────────────────────────────────────────────────────────────────
    public function approve(int $id, Request $request)
    {
        $cv = CongeValidation::findOrFail($id);

        // Super Admin : lecture seule uniquement
        if (auth()->user()->isTrueSuperAdmin()) {
            return redirect()->back()->with('warning', 'Les super admins ne peuvent pas valider les demandes.');
        }

        // Sécurité : Simple Admin ne peut valider que les demandes de son siège
        if ($cv->SiegeID !== auth()->user()->SiegeID) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        if ($cv->status !== 'en_cours') {
            return redirect()->back()->with('warning', 'Cette demande a déjà été traitée.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:Employes,ID',
            'type_conge'  => 'required|in:CP,RTT,Maladie,Autres',
            'commentaire' => 'nullable|string|max:500',
        ], [
            'employee_id.required' => 'Veuillez sélectionner un employé.',
            'employee_id.exists'   => "L'employé sélectionné n'existe pas.",
            'type_conge.required'  => 'Veuillez sélectionner un type de congé.',
            'type_conge.in'        => 'Type de congé invalide.',
        ]);

        // Créer le Conge (SiegeID auto-rempli par le boot() du modèle Conge)
        Conge::create([
            'employee_id' => $validated['employee_id'],
            'date_debut'  => $cv->date_heure_debut,
            'date_fin'    => $cv->date_heure_fin,
            'type_conge'  => $validated['type_conge'],
            'commentaire' => $validated['commentaire'] ?? null,
        ]);

        // Mettre à jour la demande
        $cv->update([
            'status'          => 'validated',
            'date_validation' => now(),
        ]);

        return redirect()->back()->with('success', "Demande de {$cv->nom_prenom} validée — congé créé.");
    }

    // ─────────────────────────────────────────────────────────────────
    // Refuser une demande → met à jour statut + raison_rejection
    // ─────────────────────────────────────────────────────────────────
    public function reject(int $id, Request $request)
    {
        $cv = CongeValidation::findOrFail($id);

        // Super Admin : lecture seule uniquement
        if (auth()->user()->isTrueSuperAdmin()) {
            return redirect()->back()->with('warning', 'Les super admins ne peuvent pas refuser les demandes.');
        }

        // Sécurité : Simple Admin ne peut traiter que les demandes de son siège
        if ($cv->SiegeID !== auth()->user()->SiegeID) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        if ($cv->status !== 'en_cours') {
            return redirect()->back()->with('warning', 'Cette demande a déjà été traitée.');
        }

        $validated = $request->validate([
            'raison_rejection' => 'required|string|max:500',
        ], [
            'raison_rejection.required' => 'Veuillez indiquer la raison du refus.',
        ]);

        $cv->update([
            'status'           => 'not_validated',
            'date_validation'  => now(),
            'raison_rejection' => $validated['raison_rejection'],
        ]);

        return redirect()->back()->with('success', "Demande de {$cv->nom_prenom} refusée.");
    }
}
