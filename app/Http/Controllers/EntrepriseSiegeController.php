<?php
// app/Http/Controllers/EntrepriseSiegeController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use App\Http\Requests\EntrepriseSiegeRequest;
use App\Repositories\EntrepriseSiegeRepository;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        // Seul un SuperAdmin peut voir tous les sièges
        if (!Gate::allows('superadmin')) {
            return redirect()->route('sieges.show', auth()->user()->SiegeID);
        }
        
        $filters = $request->only(['search', 'Actived', 'sort_by', 'sort_order']);
        $sieges = $this->repository->getFiltered($filters);
        
        return view('sieges.index', compact('sieges', 'filters'));
    }
    
    public function create()
    {
        // Seul un SuperAdmin peut créer des sièges
        if (!Gate::allows('superadmin')) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce page'));
        }
        
        return view('sieges.create');
    }
    
    public function store(EntrepriseSiegeRequest $request)
    {
        // Seul un SuperAdmin peut créer des sièges
        if (!Gate::allows('superadmin')) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce page'));
        }
        
        $siege = $this->repository->create($request->validated());
        
        return redirect()->route('sieges.index')
            ->with('success', __('Siège créé avec succès'));
    }
    
    public function show($id)
    {
        $siege = $this->repository->findById($id);
        
        // Vérifier si l'utilisateur peut accéder à ce siège
        if (!Gate::allows('access-siege', $siege->ID)) {
            abort(403, __('Vous n\'avez pas accès à ce page'));
        }
        
        // Récupérer les entreprises et employés associés
        $entreprises = $siege->entreprises()->paginate(5);
        $employes = $siege->employes()->paginate(5);
        
        return view('sieges.show', compact('siege', 'entreprises', 'employes'));
    }
    
    public function edit($id)
    {
        // Seul un SuperAdmin peut éditer des sièges
        if (!Gate::allows('superadmin')) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce page'));
        }
        
        $siege = $this->repository->findById($id);
        
        return view('sieges.edit', compact('siege'));
    }
    
    public function update(EntrepriseSiegeRequest $request, $id)
    {
        // Seul un SuperAdmin peut mettre à jour des sièges
        // if (!Gate::allows('superadmin')) {
        //     return redirect()->route('sieges.index')
        //         ->with('error', __('Vous n\'avez pas accès à ce page'));
        // }
        
        // $this->repository->update($id, $request->validated());
        
        // return redirect()->route('sieges.index')
        //     ->with('success', __('Siège modifié avec succès'));
        dd($request) ;
    }
    
    public function destroy($id)
    {
        // Seul un SuperAdmin peut supprimer des sièges
        if (!Gate::allows('superadmin')) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à ce page'));
        }
        
        $this->repository->delete($id);
        
        return redirect()->route('sieges.index')
            ->with('success', __('Siège supprimé avec succès'));
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