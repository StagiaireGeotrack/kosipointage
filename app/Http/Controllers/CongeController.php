<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use App\Services\JourOuvrableService;

class CongeController extends Controller
{
    protected $exportService;
    protected $jourOuvrableService;

    public function __construct(ExportService $exportService , JourOuvrableService $jourOuvrableService )
    {
        $this->exportService = $exportService;
        $this->jourOuvrableService = $jourOuvrableService;
    }

    public function index(Request $request)
    {
        $sieges = EntrepriseSiege::all();
        $filters = $request->only([ 'SiegeID' , 'search' , 'type_conge', 'employee_id' ]);
        
        // Le scope global s'applique automatiquement
        $query = Conge::with('employe');

        if (!empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        if (!empty($filters['search'])) {
            $query->whereHas('employe', function($q) use ($filters) {
                $q->where('Nom', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        if (!empty($filters['type_conge'])) {
            $query->where('type_conge', $filters['type_conge']);
        }
        
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        $conges = $query->orderBy('id', 'desc')->paginate(5);
        
        // Les employés sont déjà filtrés par le scope global
        $employes = Employe::orderBy('Nom')->get();
        
        return view( 'conges.index', compact('conges', 'employes', 'filters' , 'sieges') );
    }

    public function create()
    {
        $sieges = EntrepriseSiege::all();
        $employes = Employe::orderBy('Nom')->get();
        $typesConge = ['CP', 'RTT', 'Maladie', 'Autres'];
        
        return view('conges.create', compact('employes', 'typesConge', 'sieges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:Employes,ID',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_conge' => 'required|in:CP,RTT,Maladie,Autres',
            'commentaire' => 'nullable|string',
        ], [
            'employee_id.required' => 'Veuillez sélectionner un employé.',
            'employee_id.exists' => 'L\'employé sélectionné n\'existe pas.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'type_conge.required' => 'Veuillez sélectionner un type de congé.',
            'type_conge.in' => 'Le type de congé sélectionné n\'est pas valide.',
            'commentaire.string' => 'Le commentaire doit être du texte.'
        ]);

        // Le SiegeID sera automatiquement rempli par le boot()
        Conge::create($validated);

        return redirect()->route('conges.index')
            ->with('success', 'Congé créé avec succès.');
    }

    public function show(Conge $conge)
    {
        // Le scope global vérifie automatiquement l'accès
        $conge->load('employe');
        $conge->load('siege');
        return view('conges.show', compact('conge'));
    }

    public function edit(Conge $conge)
    {
        // Le scope global vérifie automatiquement l'accès
        $employes = Employe::orderBy('Nom')->get();
        $typesConge = ['CP', 'RTT', 'Maladie', 'Autres'];
        
        return view('conges.edit', compact('conge', 'employes', 'typesConge'));
    }

    public function update(Request $request, Conge $conge)
    {
        // Le scope global vérifie automatiquement l'accès
        $validated = $request->validate([
            'employee_id' => 'required|exists:Employes,ID',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_conge' => 'required|in:CP,RTT,Maladie,Autres',
            'commentaire' => 'nullable|string',
        ], [
            'employee_id.required' => 'Veuillez sélectionner un employé.',
            'employee_id.exists' => 'L\'employé sélectionné n\'existe pas.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'type_conge.required' => 'Veuillez sélectionner un type de congé.',
            'type_conge.in' => 'Le type de congé sélectionné n\'est pas valide.',
            'commentaire.string' => 'Le commentaire doit être du texte.'
        ]);

        $conge->update($validated);

        return redirect()->route('conges.index')
            ->with('success', 'Congé modifié avec succès.');
    }

    public function destroy(Conge $conge)
    {
        // Le scope global vérifie automatiquement l'accès
        $conge->delete();

        return redirect()->route('conges.index')
            ->with('success', 'Congé supprimé avec succès.');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->prepareExportData($request);
        return $this->exportService->exportToExcel($data, "Liste des congés");
    }

    public function exportPdf(Request $request)
    {
        $data = $this->prepareExportData($request);
        return $this->exportService->exportToPdf($data, "Liste des congés", 'exports.generic');
    }

    public function prepareExportData(Request $request)
    {
        $filters = $request->only(['SiegeID', 'search', 'type_conge', 'employee_id']);
        $query = Conge::with(['employe', 'siege']);
        
        if (!empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas('employe', function($q) use ($filters) {
                $q->where('Nom', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        if (!empty($filters['type_conge'])) {
            $query->where('type_conge', $filters['type_conge']);
        }
        
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        $data = $query->orderBy('date_debut', 'desc')->get()->map(function ($conge) {
            $joursOuvrables = $this->jourOuvrableService->calculerJoursOuvrables(
                $conge->date_debut, 
                $conge->date_fin,
                $conge->employe->SiegeID ?? null
            );

            $resultat_duree = "";

            if ($joursOuvrables['jours'] > 0) {
                $resultat_duree .= round($joursOuvrables['jours']) . " jour(s) ouvrable(s)";
            }

            if ($joursOuvrables['heures'] > 0) {
                if ($joursOuvrables['jours'] > 0) {
                    $resultat_duree .= " et ";
                }
                $resultat_duree .= round($joursOuvrables['heures']) . " heure(s)";
            }

            if ($joursOuvrables['jours'] == 0 && $joursOuvrables['heures'] == 0) {
                $resultat_duree = "Aucun jour ouvrable";
            }

            return [
                'ID' => $conge->ID,
                'Employé(e) ID' => $conge->employe->ID,
                'Employé(e) Nom' => $conge->employe->Nom,
                'Siège ID' => $conge->siege->ID,
                'Siège Nom' => $conge->siege->Nom,
                'Date Début' => ucfirst($conge->date_debut ? $conge->date_debut->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
                'Date Fin' => ucfirst($conge->date_fin ? $conge->date_fin->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
                'Durée' => $resultat_duree,
                'Type Congé' => $conge->type_conge ,
                'Observation / Commentaire' => $conge->commentaire,
                'Date de création' => ucfirst($conge->created_at ? $conge->created_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
            ];
        });

        return $data;
    }
}