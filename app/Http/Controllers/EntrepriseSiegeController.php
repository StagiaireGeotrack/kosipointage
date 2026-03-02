<?php
// app/Http/Controllers/EntrepriseSiegeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EntrepriseSiegeRequest;
use App\Repositories\EntrepriseSiegeRepository;

class EntrepriseSiegeController extends Controller
{
    protected $repository;
    protected $exportService;
    
    public function __construct(
        EntrepriseSiegeRepository $repository,
        ExportService $exportService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur : afficher la liste des sièges (filtrée automatiquement par SiegeScope)
        if ($user->isTrueSuperAdmin() || $user->isSeller()) {
            $filters = $request->only(['search', 'Actived', 'sort_by', 'sort_order']);
            $sieges = $this->repository->getFiltered($filters);
            
            return view('sieges.index', compact('sieges', 'filters'));
        }
        
        // Simple Admin : rediriger vers son siège s'il en a un
        if ($user->isSimpleAdmin() && $user->SiegeID) {
            return redirect()->route('sieges.show', $user->SiegeID);
        }
        
        // Simple Admin sans siège : afficher la liste vide
        $filters = $request->only(['search', 'Actived', 'sort_by', 'sort_order']);
        $sieges = $this->repository->getFiltered($filters);

        $sieges->appends($filters);
        
        return view('sieges.index', compact('sieges', 'filters'));
    }
    
    public function create()
    {
        $user = auth()->user();

        $countries = countries();
        
        // Super Admin et Vendeur peuvent créer des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        return view( 'sieges.create', compact('countries') );
    }
    
    public function store(EntrepriseSiegeRequest $request) 
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur peuvent créer des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        $validated = $request->validated() ;

        if ( $user->isSeller() ) {
            $validated["Actived"] = 0 ;
        }

        $this->repository->create($validated);
        
        return redirect()->back()->with('success', __('Siège créé avec succès'));
    }
    
    public function show($id)
    {
        $siege = $this->repository->findById($id);
        
        // Vérifier si l'utilisateur peut accéder à ce siège
        if (!Gate::allows('access-siege', $siege->ID)) {            
            $message = 'Vous n\'avez pas accès à cette page' ;
            return view( '403' , compact('message') );
        }
        
        // Récupérer les entreprises et employés associés
        $entreprises = $siege->entreprises()->paginate(5);
        $employes = $siege->employes()->paginate(5);
        
        return view('sieges.show', compact('siege', 'entreprises', 'employes'));
    }
    
    public function edit($id)
    {
        $user = auth()->user();

        $countries = countries();
        
        // Super Admin et Vendeur peuvent éditer des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        $siege = $this->repository->findById($id);
        
        // Vérifier que le vendeur a accès à ce siège spécifique
        if ($user->isSeller() && !$user->hasAccessToSiege($siege->ID)) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce siège'));
        }
        
        return view('sieges.edit', compact('siege', 'id' , 'countries'));
    }
    
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur peuvent mettre à jour des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        $siege = $this->repository->findById($id);
        
        // Vérifier que le vendeur a accès à ce siège spécifique
        if ($user->isSeller() && !$user->hasAccessToSiege($siege->ID)) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce siège'));
        }
        
        $validated = $request->validated();
    
        // Forcer Actived à 0 si absent
        $validated['Actived'] = $request->has('Actived') ? 1 : 0;

        $this->repository->update($id, $validated);
        
        return redirect()->back()->with('success', __('Siège modifié avec succès'));
    }

    public function update_siege(Request $request)
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur peuvent mettre à jour des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        $id = $request->ID;
        
        // Vérifier que le vendeur a accès à ce siège spécifique
        if ($user->isSeller() && !$user->hasAccessToSiege($id)) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce siège'));
        }

        $validated = $request->validate([
            'ID' => 'required|integer',
            'Pays' => 'nullable|string|max:255',
            'Nom' => [
                'required',
                Rule::unique(EntrepriseSiege::class , 'Nom')->ignore($id), 
            ],
            'Nom_Lieu_Ville' => 'required'
        ], [
            'Nom.required' => 'Le champ nom est obligatoire.',
            'Nom.unique' => 'Ce nom de siège existe déjà.',
            'Nom_Lieu_Ville.required' => 'Le champ Adresse ou ville est obligatoire.',
            'Pays.string' => __('Le champ Pays doit être une chaîne de caractères'),
            'Pays.max' => __('Le champ Pays ne doit pas dépasser :max caractères'),
        ]);

        $validated['Actived'] = $request->has('Actived') ? 1 : 0;

        DB::table('Entreprises')->where('SiegeID', $id)->update(['Actived' => $validated['Actived']]);
        DB::table('Employes')->where('SiegeID', $id)->update(['Actived' => $validated['Actived']]);
        
        $siege = EntrepriseSiege::findOrFail($id); 
        $siege->update($validated);
        
        return redirect()->back()->with('success', __('Siège modifié avec succès'));
    }
    
    public function destroy($id)
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur peuvent supprimer des sièges
        if (!$user->isTrueSuperAdmin() && !$user->isSeller()) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        $siege = $this->repository->findById($id);
        
        // Vérifier que le vendeur a accès à ce siège spécifique
        if ($user->isSeller() && !$user->hasAccessToSiege($siege->ID)) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce siège'));
        }
        
        $this->repository->delete($id);
        
        return redirect()->back()->with('success', __('Siège supprimé avec succès'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'Actived']);
        $sieges = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($sieges, 'Sièges');
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'Actived']);
        $sieges = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($sieges, 'Liste des sièges', 'exports.generic');
    }
}