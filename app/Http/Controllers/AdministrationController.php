<?php
// app/Http/Controllers/AdministrationController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use App\Http\Requests\AdministrationRequest;
use App\Repositories\AdministrationRepository;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministrationController extends Controller
{
    protected $repository;
    protected $exportService;
    
    public function __construct(
        AdministrationRepository $repository,
        ExportService $exportService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin', 'sort_by', 'sort_order']);
        $administrateurs = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all(); // Pour le filtre par siège
        
        return view('administration.index', compact('administrateurs', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        return view('administration.create', compact('sieges'));
    }
    
    public function store(AdministrationRequest $request)
    {
        $data = $request->validated();
        
        // Transformer password en Password_
        if (isset($data['password'])) {
            $data['Password_'] = sha1($data['password']);
            unset($data['password']);
        }
        
        unset($data['password_confirmation']);
        
        $admin = $this->repository->create($data);
        
        return redirect()->route('administrateurs.index')
            ->with('success', __('app.admin_created_successfully'));
    }
    
    public function show($id)
    {
        $administrateur = $this->repository->findById($id);
        return view('administration.show', compact('administrateur'));
    }
    
    public function edit($id)
    {
        $administrateur = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        
        // Empêcher la modification du compte courant
        if ($administrateur->ID === auth()->user()->ID) {
            return redirect()->route('profile.edit')
                ->with('error', __('app.edit_own_account_in_profile'));
        }
        
        return view('administration.edit', compact('administrateur', 'sieges'));
    }
    
    public function update(AdministrationRequest $request, $id)
    {
        $data = $request->validated();
        
        // Transformer password en Password_ si présent
        if (!empty($data['password'])) {
            $data['Password_'] = sha1($data['password']);
        }
        
        // Supprimer les champs inutiles
        unset($data['password']);
        unset($data['password_confirmation']);
        
        $admin = $this->repository->update($id, $data);
        
        return redirect()->route('administrateurs.index')
            ->with('success', __('app.admin_updated_successfully'));
    }
    
    public function destroy($id)
    {
        // Empêcher la suppression du compte courant
        if ((int)$id === auth()->user()->ID) {
            return redirect()->route('administrateurs.index')
                ->with('error', __('app.cannot_delete_own_account'));
        }
        
        $this->repository->delete($id);
        
        return redirect()->route('administrateurs.index')
            ->with('success', __('app.admin_deleted_successfully'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin']);
        $administrateurs = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($administrateurs, __('app.administrators'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin']);
        $administrateurs = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($administrateurs, __('app.administrators'), 'exports.administrateurs');
    }
}