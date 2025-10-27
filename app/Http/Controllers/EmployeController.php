<?php
// app/Http/Controllers/EmployeController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Http\Requests\EmployeRequest;
use App\Repositories\EmployeRepository;
use App\Services\ExportService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    protected $repository;
    protected $exportService;
    protected $fileService;
    
    public function __construct(
        EmployeRepository $repository,
        ExportService $exportService,
        FileStorageService $fileService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
        $this->fileService = $fileService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup',
            'sort_by', 'sort_order'
        ]);
        
        $employes = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all(); // Pour le filtre par siège
        
        return view('employes.index', compact('employes', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        return view('employes.create', compact('sieges'));
    }
    
    public function store(EmployeRequest $request)
    {
        $employe = $this->repository->create($request->validated());
        
        if ($request->hasFile('FaceEncodingFile')) {
            $this->fileService->storeInDatabase($request->file('FaceEncodingFile'), 'FaceEncodingPath', $employe, 'ID');
            
            // Mettre à jour HasFaceSetup si un fichier d'encodage est téléchargé
            $employe->HasFaceSetup = true;
            $employe->save();
        }
        
        return redirect()->route('employes.index')
            ->with('success', __('app.employee_created_successfully'));
    }
    
    public function show($id)
    {
        $employe = $this->repository->findById($id);
        $pointages = $employe->pointages()->latest('timestamp_')->paginate(5);
        
        return view('employes.show', compact('employe', 'pointages'));
    }
    
    public function edit($id)
    {
        $employe = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        
        return view('employes.edit', compact('employe', 'sieges'));
    }
    
    public function update(EmployeRequest $request, $id)
    {
        $employe = $this->repository->update($id, $request->validated());
        
        if ($request->hasFile('FaceEncodingFile')) {
            $this->fileService->storeInDatabase($request->file('FaceEncodingFile'), 'FaceEncodingPath', $employe, 'ID');
            
            // Mettre à jour HasFaceSetup si un fichier d'encodage est téléchargé
            $employe->HasFaceSetup = true;
            $employe->save();
        }
        
        return redirect()->route('employes.index')
            ->with('success', __('app.employee_updated_successfully'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        
        return redirect()->route('employes.index')
            ->with('success', __('app.employee_deleted_successfully'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);
        
        $employes = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($employes, __('app.employees'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);
        
        $employes = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($employes, __('app.employees'), 'exports.employes');
    }
    
    public function getFaceEncoding($id)
    {
        $employe = Employe::findOrFail($id);
        
        if (!$employe->FaceEncodingPath) {
            abort(404, __('app.face_encoding_not_found'));
        }
        
        $imageData = $this->fileService->retrieveFromDatabase($employe, 'FaceEncodingPath');
        
        return response($imageData)
            ->header('Content-Type', 'image/png');
    }
    
    public function getFaceThumbnail($id)
    {
        $employe = Employe::findOrFail($id);
        
        if (!$employe->FaceEncodingPath) {
            abort(404, __('app.face_encoding_not_found'));
        }
        
        $imageData = $this->fileService->retrieveFromDatabase($employe, 'FaceEncodingPath');
        $thumbnail = $this->fileService->createThumbnail($imageData);
        
        return response($thumbnail)
            ->header('Content-Type', 'image/png');
    }
}