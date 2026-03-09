<?php
// app/Http/Controllers/AdministrationController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use App\Http\Requests\AdministrationRequest;
use App\Models\Administration;
use App\Repositories\AdministrationRepository;
use App\Services\ExportService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

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

        $administrateurs->appends($filters);

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
        
        $administrateur = $this->repository->create($data);

        ActivityLogService::log(
            action: 'create',
            modelType: 'Administration',
            modelId: $administrateur->ID,
            modelLabel: $administrateur->Identifiant_email,
        );

        return redirect()->back()->with('success', __('Administrateur simple créé'));
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
                ->with('error', __('Ce compte ne peut pas être modifié'));
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
        
        $this->repository->update($id, $data);

        ActivityLogService::log(
            action: 'update',
            modelType: 'Administration',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Compte modifié avec succès'));
    }
    
    public function destroy($id)
    {
        $user = auth()->user();        
        if ( !$user->isTrueSuperAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        // Empêcher la suppression du compte courant
        if ((int)$id === auth()->user()->ID) {
            return redirect()->back()->with('error', __('Ce compte ne peut pas être supprimé'));
        }
        
        Administration::where("ID" , $id)->update(["deleted" => true , "Actived" => false ]);
        // $this->repository->delete($id);

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Administration',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Compte supprimé avec succès'));
    }
    
    public function reset($id)
    {
        $user = auth()->user();        
        if ( !$user->isTrueSuperAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }
        
        // Empêcher la suppression du compte courant
        if ((int)$id === auth()->user()->ID) {
            return redirect()->back()->with('error', __('Ce compte ne peut pas être supprimé'));
        }
        
        Administration::where("ID" , $id)->update(["deleted" => false , "Actived" => true ]);
        // $this->repository->delete($id);

        ActivityLogService::log(
            action: 'reset',
            modelType: 'Administration',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Compte supprimé avec succès'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin']);
        $administrateurs = $this->repository->getAllForExport($filters);
        
        ActivityLogService::log(action: 'export_excel', modelType: 'Administration');
        return $this->exportService->exportToExcel($administrateurs, __('Administrateurs'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin']);
        $administrateurs = $this->repository->getAllForExport($filters);
        
        ActivityLogService::log(action: 'export_pdf', modelType: 'Administration');
        return $this->exportService->exportToPdf($administrateurs, "Liste des administrateurs" , 'exports.generic' );
    }
}