<?php
// app/Http/Controllers/EntrepriseController.php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\EntrepriseSiege;
use App\Http\Requests\EntrepriseRequest;
use App\Repositories\EntrepriseRepository;
use App\Services\ExportService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    protected $repository;
    protected $exportService;
    protected $fileService;
    
    public function __construct(
        EntrepriseRepository $repository,
        ExportService $exportService,
        FileStorageService $fileService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
        $this->fileService = $fileService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived', 'sort_by', 'sort_order']);
        $entreprises = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all();
        
        return view('entreprises.index', compact('entreprises', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        return view('entreprises.create', compact('sieges'));
    }
    
    public function store(EntrepriseRequest $request)
    {
        $entreprise = $this->repository->create($request->validated());
        
        if ($request->hasFile('Logo')) {
            $this->fileService->storeInDatabase($request->file('Logo'), 'Logo', $entreprise, 'ID');
        }
        
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement créé avec succès.'));
    }
    
    public function show($id)
    {
        $entreprise = $this->repository->findById($id);
        return view('entreprises.show', compact('entreprise'));
    }
    
    public function edit($id)
    {
        $entreprise = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        
        return view('entreprises.edit', compact('entreprise', 'sieges'));
    }
    
    public function update(EntrepriseRequest $request, $id)
    {
        $entreprise = $this->repository->update($id, $request->validated());
        
        if ($request->hasFile('Logo')) {
            $this->fileService->storeInDatabase($request->file('Logo'), 'Logo', $entreprise, 'ID');
        }
        
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement modifié avec succès.'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement supprimé avec succès.'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived']);
        $entreprises = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($entreprises, 'Entreprises');
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived']);
        $entreprises = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($entreprises, 'Entreprises', 'exports.entreprises');
    }
    
    public function getLogo($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $logoData = $this->fileService->retrieveFromDatabase($entreprise, 'Logo');
        
        if (!$logoData) {
            abort(404, 'Logo non trouvé');
        }
        
        $mimeType = 'image/png'; // Vous pouvez déterminer dynamiquement le type MIME si nécessaire
        
        return response($logoData)
            ->header('Content-Type', $mimeType);
    }
    
    public function getLogoThumbnail($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $logoData = $this->fileService->retrieveFromDatabase($entreprise, 'Logo');
        
        if (!$logoData) {
            abort(404, 'Logo non trouvé');
        }
        
        $thumbnail = $this->fileService->createThumbnail($logoData);
        
        return response($thumbnail)
            ->header('Content-Type', 'image/png');
    }
}