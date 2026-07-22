<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CongeValidation;
use Illuminate\Support\Facades\Auth;

class EmployeCongeController extends Controller
{
    /**
     * Helper pour parser les dates provenant des filtres
     */
    private function parseDate($dateString)
    {
        try {
            if (strpos($dateString, '/') !== false) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $dateString);
            }
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Display a listing of the leave requests.
     */
    public function index(Request $request)
    {
        $employe = Auth::guard('employe')->user();
        
        $query = CongeValidation::where('matricule', $employe->num_mat)
            ->where('SiegeID', $employe->SiegeID);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_debut')) {
            if ($date = $this->parseDate($request->date_debut)) {
                $query->where('date_creation', '>=', $date->startOfDay());
            }
        }

        if ($request->filled('date_fin')) {
            if ($date = $this->parseDate($request->date_fin)) {
                $query->where('date_creation', '<=', $date->endOfDay());
            }
        }

        $conges = $query->orderBy('date_creation', 'desc')->paginate(5);
        $conges->appends($request->only(['status', 'date_debut', 'date_fin']));
            
        return view('employe_dashboard.conges.index', compact('conges'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        return view('employe_dashboard.conges.create');
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_heure_debut' => 'required|date',
            'date_heure_fin' => 'required|date|after:date_heure_debut',
            'raison' => 'required|string|max:1000',
        ], [
            'date_heure_debut.required' => 'La date et heure de début sont obligatoires.',
            'date_heure_fin.required' => 'La date et heure de fin sont obligatoires.',
            'date_heure_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'raison.required' => 'Le motif du congé est obligatoire.',
        ]);

        $employe = Auth::guard('employe')->user();

        CongeValidation::create([
            'SiegeID' => $employe->SiegeID,
            'matricule' => $employe->num_mat,
            'nom_prenom' => $employe->Nom,
            'email' => $employe->email,
            'telephone' => $employe->telephone,
            'date_heure_debut' => $request->date_heure_debut,
            'date_heure_fin' => $request->date_heure_fin,
            'status' => 'en_cours',
            'raison' => $request->raison,
            'date_creation' => now(),
        ]);

        return redirect()->route('employe.conges.index')->with('success', __('Votre demande de congé a été soumise avec succès et est en attente de validation.'));
    }

    /**
     * Remove the specified leave request from storage.
     */
    public function destroy($id)
    {
        $employe = Auth::guard('employe')->user();
        
        $conge = CongeValidation::where('id', $id)
            ->where('matricule', $employe->num_mat)
            ->where('SiegeID', $employe->SiegeID)
            ->firstOrFail();

        if ($conge->status !== 'en_cours') {
            return redirect()->route('employe.conges.index')->with('error', __('Vous ne pouvez pas supprimer une demande qui a déjà été traitée (validée ou refusée).'));
        }

        $conge->delete();

        return redirect()->route('employe.conges.index')->with('success', __('Votre demande de congé a été annulée.'));
    }
}
