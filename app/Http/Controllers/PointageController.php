<?php
// app/Http/Controllers/PointageController.php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Http\Requests\PointageRequest;
use App\Repositories\PointageRepository;
use App\Services\ExportService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PointageController extends Controller
{
    protected $repository;
    protected $exportService;
    protected $fileService;
    
    public function __construct(
        PointageRepository $repository,
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
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method', 
            'date_from', 'date_to', 'sort_by', 'sort_order'
        ]);
        
        $pointages = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all(); // Pour le filtre par siège
        
        // Récupérer les employés du siège sélectionné, ou tous si aucun siège sélectionné
        if (!empty($filters['SiegeID'])) {
            $employes = Employe::where('SiegeID', $filters['SiegeID'])->get();
        } else {
            // Si l'utilisateur est un Admin standard, limiter aux employés de son siège
            if (!auth()->user()->IsSuperAdmin) {
                $employes = Employe::where('SiegeID', auth()->user()->SiegeID)->get();
            } else {
                $employes = Employe::all();
            }
        }
        
        return view('pointages.index', compact('pointages', 'sieges', 'employes', 'filters'));
    }
    
    public function create()
    {
        // Récupérer les sièges auxquels l'utilisateur a accès
        if (auth()->user()->IsSuperAdmin) {
            $sieges = EntrepriseSiege::all();
            $employes = collect(); // Collection vide, sera remplie par AJAX
        } else {
            $sieges = EntrepriseSiege::where('ID', auth()->user()->SiegeID)->get();
            $employes = Employe::where('SiegeID', auth()->user()->SiegeID)->get();
        }
        
        return view('pointages.create', compact('sieges', 'employes'));
    }
    
    public function store(PointageRequest $request)
    {
        $data = $request->validated();
        
        // Définir l'horodatage
        if (empty($data['timestamp_'])) {
            $data['timestamp_'] = Carbon::now();
        }
        
        $pointage = $this->repository->create($data);
        
        // Traiter la photo si fournie
        if ($request->hasFile('photo')) {
            $this->fileService->storeInDatabase($request->file('photo'), 'photo_path', $pointage, 'ID');
        }
        
        return redirect()->route('pointages.index')
            ->with('success', __('Pointage créé avec succès'));
    }
    
    public function show($id)
    {
        $pointage = $this->repository->findById($id);
        return view('pointages.show', compact('pointage'));
    }
    
    public function edit($id)
    {
        $pointage = $this->repository->findById($id);
        
        // Récupérer les sièges auxquels l'utilisateur a accès
        if (auth()->user()->IsSuperAdmin) {
            $sieges = EntrepriseSiege::all();
            // Récupérer les employés du siège actuel du pointage
            $employes = Employe::where('SiegeID', $pointage->SiegeID)->get();
        } else {
            $sieges = EntrepriseSiege::where('ID', auth()->user()->SiegeID)->get();
            $employes = Employe::where('SiegeID', auth()->user()->SiegeID)->get();
        }
        
        return view('pointages.edit', compact('pointage', 'sieges', 'employes'));
    }
    
    public function update(PointageRequest $request, $id)
    {
        $pointage = $this->repository->update($id, $request->validated());
        
        // Traiter la photo si fournie
        if ($request->hasFile('photo')) {
            $this->fileService->storeInDatabase($request->file('photo'), 'photo_path', $pointage, 'ID');
        }
        
        return redirect()->route('pointages.index')
            ->with('success', __('Pointage modifié avec succès'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        
        return redirect()->route('pointages.index')
            ->with('success', __('Pointage supprimé avec succès'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method', 
            'date_from', 'date_to'
        ]);
        
        $pointages = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($pointages, __('Pointages'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method', 
            'date_from', 'date_to'
        ]);
        
        $pointages = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($pointages, __('Pointages'), 'exports.pointages');
    }
    
    public function getPhoto($id)
    {
        $pointage = Pointage::findOrFail($id);
        
        if (!$pointage->photo_path) {
            abort(404, __('Face non trouvée'));
        }
        
        $imageData = $this->fileService->retrieveFromDatabase($pointage, 'photo_path');
        
        return response($imageData)
            ->header('Content-Type', 'image/png');
    }
    
    public function getPhotoThumbnail($id)
    {
        $pointage = Pointage::findOrFail($id);
        
        if (!$pointage->photo_path) {
            abort(404, __('Face non trouvée'));
        }
        
        $imageData = $this->fileService->retrieveFromDatabase($pointage, 'photo_path');
        $thumbnail = $this->fileService->createThumbnail($imageData);
        
        return response($thumbnail)
            ->header('Content-Type', 'image/png');
    }
    
    public function getEmployesBySiege(Request $request)
    {
        $siegeId = $request->input('siege_id');
        
        if (!$siegeId) {
            return response()->json([]);
        }
        
        $employes = Employe::where('SiegeID', $siegeId)
            ->where('Actived', 1)
            ->get(['ID', 'Nom', 'BadgeID']);
            
        return response()->json($employes);
    }
}