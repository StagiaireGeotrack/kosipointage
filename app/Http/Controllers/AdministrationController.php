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
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin', 'role', 'sort_by', 'sort_order']);
        
        // Sécurité Admin Simple : Forcer les filtres pour ne voir que ses Managers
        if (auth()->check() && auth()->user()->isSimpleAdmin()) {
            $filters['SiegeID'] = auth()->user()->SiegeID;
            $filters['role'] = 'manager_simple_admin';
            $filters['IsSuperAdmin'] = 0;
        }

        $administrateurs = $this->repository->getFiltered($filters);

        $administrateurs->appends($filters);

        $sieges = EntrepriseSiege::all(); // Pour le filtre par siège
        
        $pageTitle = __('Administrateurs');
        if (isset($filters['role'])) {
            if ($filters['role'] === 'manager_super_admin') {
                $pageTitle = __('Gestion des Managers Super Admin');
            } elseif ($filters['role'] === 'manager_simple_admin') {
                $pageTitle = __('Gestion des Managers Admin Simple');
            }
        }
        
        return view('administration.index', compact('administrateurs', 'sieges', 'filters', 'pageTitle'));
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

        // Sécurité Manager : Interdit de modifier un pair ou supérieur
        if (auth()->user()->isManagerSuperAdmin() && $administrateur->isTrueSuperAdmin()) {
            return redirect()->route('administrateurs.index')
                ->with('error', __('Vous n\'êtes pas autorisé à modifier un administrateur supérieur ou de même niveau.'));
        }

        // Sécurité Admin Simple : Ne peut modifier que ses propres Managers
        if (auth()->user()->isSimpleAdmin()) {
            if (!$administrateur->isManagerSimpleAdmin() || $administrateur->SiegeID !== auth()->user()->SiegeID) {
                return redirect()->route('administrateurs.index')
                    ->with('error', __('Vous n\'êtes pas autorisé à modifier ce compte.'));
            }
        }
        
        return view('administration.edit', compact('administrateur', 'sieges'));
    }
    
    public function update(AdministrationRequest $request, $id)
    {
        $administrateur = $this->repository->findById($id);
        
        // Sécurité Manager : Interdit de modifier un pair ou supérieur
        if (auth()->user()->isManagerSuperAdmin() && $administrateur->isTrueSuperAdmin()) {
            return redirect()->route('administrateurs.index')
                ->with('error', __('Vous n\'êtes pas autorisé à modifier un administrateur supérieur ou de même niveau.'));
        }

        // Sécurité Admin Simple : Ne peut modifier que ses propres Managers
        if (auth()->user()->isSimpleAdmin()) {
            if (!$administrateur->isManagerSimpleAdmin() || $administrateur->SiegeID !== auth()->user()->SiegeID) {
                return redirect()->route('administrateurs.index')
                    ->with('error', __('Vous n\'êtes pas autorisé à modifier ce compte.'));
            }
        }

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
        if ( !$user->isTrueSuperAdmin() && !$user->isManagerSuperAdmin() && !$user->isSimpleAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $administrateur = Administration::findOrFail($id);
        
        // Sécurité Manager : Interdit de supprimer un pair ou supérieur
        if ($user->isManagerSuperAdmin() && $administrateur->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Vous n\'êtes pas autorisé à supprimer un administrateur supérieur ou de même niveau.'));
        }

        // Sécurité Admin Simple : Ne peut supprimer que ses propres Managers
        if ($user->isSimpleAdmin()) {
            if (!$administrateur->isManagerSimpleAdmin() || $administrateur->SiegeID !== $user->SiegeID) {
                return redirect()->back()->with('error', __('Vous n\'êtes pas autorisé à supprimer ce compte.'));
            }
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
        if ( !$user->isTrueSuperAdmin() && !$user->isManagerSuperAdmin() && !$user->isSimpleAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $administrateur = Administration::findOrFail($id);
        
        // Sécurité Manager : Interdit de réinitialiser un pair ou supérieur
        if ($user->isManagerSuperAdmin() && $administrateur->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Vous n\'êtes pas autorisé à réinitialiser un administrateur supérieur ou de même niveau.'));
        }

        // Sécurité Admin Simple : Ne peut réinitialiser que ses propres Managers
        if ($user->isSimpleAdmin()) {
            if (!$administrateur->isManagerSimpleAdmin() || $administrateur->SiegeID !== $user->SiegeID) {
                return redirect()->back()->with('error', __('Vous n\'êtes pas autorisé à réinitialiser ce compte.'));
            }
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
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin', 'role']);
        if (auth()->check() && auth()->user()->isSimpleAdmin()) {
            $filters['SiegeID'] = auth()->user()->SiegeID;
            $filters['role'] = 'manager_simple_admin';
            $filters['IsSuperAdmin'] = 0;
        }
        $administrateurs = $this->repository->getAllForExport($filters);
        
        ActivityLogService::log(action: 'export_excel', modelType: 'Administration');
        return $this->exportService->exportToExcel($administrateurs, __('Administrateurs'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'IsSuperAdmin', 'role']);
        if (auth()->check() && auth()->user()->isSimpleAdmin()) {
            $filters['SiegeID'] = auth()->user()->SiegeID;
            $filters['role'] = 'manager_simple_admin';
            $filters['IsSuperAdmin'] = 0;
        }
        $administrateurs = $this->repository->getAllForExport($filters);
        
        ActivityLogService::log(action: 'export_pdf', modelType: 'Administration');
        return $this->exportService->exportToPdf($administrateurs, "Liste des administrateurs" , 'exports.generic' );
    }
}