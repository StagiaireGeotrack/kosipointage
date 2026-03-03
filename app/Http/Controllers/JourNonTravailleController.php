<?php
// app/Http/Controllers/JourNonTravailleController.php

namespace App\Http\Controllers;

use App\Http\Requests\JourNonTravailleRequest;
use App\Models\EntrepriseSiege;
use App\Repositories\JourNonTravailleRepository;
use App\Services\ExportService;
use Illuminate\Http\Request;

class JourNonTravailleController extends Controller
{
    protected $repository;
    protected $exportService;
    
    public function __construct(
        JourNonTravailleRepository $repository,
        ExportService $exportService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Type', 'annee', 'Recurrent', 'sort_by', 'sort_order']);
        $joursNonTravailles = $this->repository->getFiltered($filters);
        $joursNonTravailles->appends($filters);
        $sieges = EntrepriseSiege::all();
        
        // Liste des années disponibles (5 dernières et 5 prochaines)
        $annees = range(date('Y') - 5, date('Y') + 5);
        
        return view('jours-non-travailles.index', compact('joursNonTravailles', 'sieges', 'annees', 'filters'));
    }
    
    public function create()
    { 
        $admin_connected = Auth()->user() ;
        $siege_id = $admin_connected->SiegeID ?? null  ; 
        $sieges = EntrepriseSiege::all();
        return view('jours-non-travailles.create', compact('sieges' , 'siege_id'));
    }
    
    public function store(JourNonTravailleRequest $request)
    {
        $this->repository->create($request->validated());
        
        return redirect()->back()->with('success', __('Jour non travaillé créé avec succès'));
    }
    
    public function show($id)
    {
        $jourNonTravaille = $this->repository->findById($id);
        return view('jours-non-travailles.show', compact('jourNonTravaille'));
    }
    
    public function edit($id)
    {
        $jourNonTravaille = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        return view('jours-non-travailles.edit', compact('jourNonTravaille', 'sieges'));
    }
    
    public function update(JourNonTravailleRequest $request, $id)
    {
        $this->repository->update($id, $request->validated());
        
        return redirect()->back()->with('success', __('Jour non travaillé modifié avec succès'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        
        return redirect()->back()->with('success', __('Jour non travaillé supprimé avec succès'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Type', 'annee', 'Recurrent']);
        $joursNonTravailles = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($joursNonTravailles, __('Jours non travaillés'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Type', 'annee', 'Recurrent']);
        $joursNonTravailles = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($joursNonTravailles, 'Liste des jours non travaillés', 'exports.generic');
    }
}