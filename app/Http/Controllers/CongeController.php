<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use Illuminate\Http\Request;

class CongeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type_conge', 'employee_id']);
        
        $query = Conge::with('employe');
        
        // Filtre par recherche (nom employé)
        if (!empty($filters['search'])) {
            $query->whereHas('employe', function($q) use ($filters) {
                $q->where('Nom', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        // Filtre par type de congé
        if (!empty($filters['type_conge'])) {
            $query->where('type_conge', $filters['type_conge']);
        }
        
        // Filtre par employé
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        $conges = $query->orderBy('created_at', 'desc')->paginate(15);
        $employes = Employe::orderBy('Nom')->get();
        
        return view('conges.index', compact('conges', 'employes', 'filters'));
    }

    public function create()
    {
        $employes = Employe::orderBy('Nom')->get();
        $typesConge = ['CP', 'RTT', 'Maladie', 'Autres'];
        
        return view('conges.create', compact('employes', 'typesConge'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:Employes,ID',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_conge' => 'required|in:CP,RTT,Maladie,Autres',
            'commentaire' => 'nullable|string|max:500',
        ]);

        Conge::create($validated);

        return redirect()->route('conges.index')
            ->with('success', 'Congé créé avec succès.');
    }

    public function show(Conge $conge)
    {
        $conge->load('employe');
        return view('conges.show', compact('conge'));
    }

    public function edit(Conge $conge)
    {
        $employes = Employe::orderBy('Nom')->get();
        $typesConge = ['CP', 'RTT', 'Maladie', 'Autres'];
        
        return view('conges.edit', compact('conge', 'employes', 'typesConge'));
    }

    public function update(Request $request, Conge $conge)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:Employes,ID',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_conge' => 'required|in:CP,RTT,Maladie,Autres',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $conge->update($validated);

        return redirect()->route('conges.index')
            ->with('success', 'Congé modifié avec succès.');
    }

    public function destroy(Conge $conge)
    {
        $conge->delete();

        return redirect()->route('conges.index')
            ->with('success', 'Congé supprimé avec succès.');
    }
}