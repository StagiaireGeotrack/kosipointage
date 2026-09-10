<?php
// app/Http/Controllers/PointageController.php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Http\Requests\PointageRequest;
use App\Models\Entreprise;
use App\Repositories\PointageRepository;
use App\Services\ExportService;
use App\Services\FileStorageService;
use App\Services\ActivityLogService;
use App\Traits\EmployeeAccessTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PointageController extends Controller
{
    use EmployeeAccessTrait;

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

    /* =========================================================
       HELPERS SUPERVISOR
       ========================================================= */

    /**
     * Retourne les IDs accessibles au Supervisor, ou null pour les autres rôles.
     */
    private function getSupervisorAccessibleIds(): ?array
    {
        $user = auth()->user();
        if ($user && $user->isSupervisor()) {
            return $this->getAccessibleEmployeeIds($user);
        }
        return null;
    }

    /**
     * Vérifie qu'un employé est accessible par le Supervisor connecté.
     */
    private function ensureEmployeeAccessible($employeeId): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSupervisor()) {
            return;
        }
        $accessible = $this->getAccessibleEmployeeIds($user);
        if (!in_array((int) $employeeId, array_map('intval', $accessible), true)) {
            abort(403, 'Accès non autorisé à cet employé.');
        }
    }

    /**
     * Vérifie qu'un pointage est accessible par le Supervisor connecté.
     */
    private function ensurePointageAccessible($pointage): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSupervisor()) {
            return;
        }
        $this->ensureEmployeeAccessible($pointage->employee_id);
    }

    /* =========================================================
       INDEX
       ========================================================= */

    public function index(Request $request)
    {
        $user = auth()->user();
        $filters = $request->only([
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method',
            'date_from', 'date_to', 'sort_by', 'sort_order'
        ]);

        // ✅ Filtre Supervisor (null pour les autres rôles)
        $accessibleIds = $this->getSupervisorAccessibleIds();

        $pointages = $this->repository->getFiltered($filters, 5, $accessibleIds);
        $pointages->appends($filters);

        $sieges = EntrepriseSiege::all();

        // Récupérer les employés du siège sélectionné
        if (!empty($filters['SiegeID'])) {
            $employes = Employe::where('SiegeID', $filters['SiegeID'])->get();
        } else {
            if (!$user->IsSuperAdmin) {
                $employes = Employe::where('SiegeID', $user->SiegeID)->get();
            } else {
                $employes = Employe::all();
            }
        }

        // ✅ Filtre Supervisor : ne proposer que les employés accessibles
        if ($accessibleIds !== null) {
            $employes = $employes->filter(function ($e) use ($accessibleIds) {
                return in_array((int) $e->ID, array_map('intval', $accessibleIds), true);
            })->values();
        }

        return view('pointages.index', compact('pointages', 'sieges', 'employes', 'filters'));
    }

    /* =========================================================
       SHOW DETAILS
       ========================================================= */

    public function showDetails($employe, $date, $type_travail = null)
    {
        // ✅ Filtre Supervisor : refuser si employé hors périmètre
        $this->ensureEmployeeAccessible($employe);

        $employe = Employe::findOrFail($employe);
        $date = Carbon::parse($date);

        $query = Pointage::where('employee_id', $employe->ID);

        if ($type_travail) {
            if (strtoupper($type_travail) === 'NUIT') {
                $dateDebut = $date->copy()->setTime(21, 0, 1);
                $dateFin = $date->copy()->addDay()->setTime(6, 59, 59);
                $query->whereBetween('timestamp_', [$dateDebut, $dateFin]);
            } else {
                $dateDebut = $date->copy()->setTime(7, 0, 0);
                $dateFin = $date->copy()->setTime(21, 0, 0);
                $query->whereBetween('timestamp_', [$dateDebut, $dateFin]);
            }
        } else {
            $dateDebut = $date->copy()->setTime(7, 0, 0);
            $dateFin = $date->copy()->setTime(21, 0, 0);
            $query->whereBetween('timestamp_', [$dateDebut, $dateFin]);
        }

        $pointages = $query->orderBy('timestamp_')->get();

        return view('pointages.details', compact('employe', 'date', 'pointages', 'type_travail'));
    }

    /* =========================================================
       CREATE
       ========================================================= */

    public function create()
    {
        $admin_connected = auth()->user();
        $siege_id = $admin_connected->SiegeID ?? null;

        if (auth()->user()->IsSuperAdmin) {
            $sieges = EntrepriseSiege::all();
            $employes = collect();
            $sites = Entreprise::all();
        } else {
            $sieges = EntrepriseSiege::where('ID', auth()->user()->SiegeID)->get();
            $employes = Employe::where('SiegeID', auth()->user()->SiegeID)
                                ->where('deleted', 0)
                                ->get();
            $sites = Entreprise::where('SiegeID', auth()->user()->SiegeID)->get();
        }

        return view('pointages.create', compact('sieges', 'employes', 'sites', 'siege_id'));
    }

    /* =========================================================
       STORE
       ========================================================= */

    public function store(PointageRequest $request)
    {
        $data = $request->validated();

        if (empty($data['timestamp_'])) {
            $data['timestamp_'] = Carbon::now();
        }

        $pointage = $this->repository->create($data);

        if ($request->hasFile('photo')) {
            $this->fileService->storeInDatabase($request->file('photo'), 'photo_path', $pointage, 'ID');
        }

        ActivityLogService::log(
            action: 'create',
            modelType: 'Pointage',
            modelId: $pointage->ID,
            modelLabel: "Employé #{$pointage->employee_id} — {$pointage->type_} à " . \Carbon\Carbon::parse($pointage->timestamp_)->format('d/m/Y H:i'),
        );

        return redirect()->back()->with('success', __('Pointage créé avec succès'));
    }

    /* =========================================================
       SHOW
       ========================================================= */

    public function show($id)
    {
        $pointage = $this->repository->findById($id);

        // ✅ Filtre Supervisor : refuser si pointage hors périmètre
        $this->ensurePointageAccessible($pointage);

        return view('pointages.show', compact('pointage'));
    }

    /* =========================================================
       EDIT
       ========================================================= */

    public function edit($id)
    {
        $pointage = $this->repository->findById($id);
        $employee = Employe::find($pointage->employee_id);

        if (!$employee->Actived || $employee->deleted) {
            return redirect()->back()->with('error', __('Employé désactivé ou supprimé pour le moment'));
        }

        if (auth()->user()->IsSuperAdmin) {
            $sieges = EntrepriseSiege::all();
            $employes = Employe::where('SiegeID', $pointage->SiegeID)->get();
            $sites = Entreprise::where('SiegeID', $pointage->SiegeID)->get();
        } else {
            $sieges = EntrepriseSiege::where('ID', auth()->user()->SiegeID)->get();
            $employes = Employe::where('SiegeID', auth()->user()->SiegeID)
                                ->where('deleted', 0)
                                ->get();
            $sites = Entreprise::where('SiegeID', auth()->user()->SiegeID)->get();
        }

        return view('pointages.edit', compact('pointage', 'sieges', 'employes', 'sites'));
    }

    /* =========================================================
       UPDATE
       ========================================================= */

    public function update(PointageRequest $request, $id)
    {
        $pointage = $this->repository->update($id, $request->validated());

        if ($request->hasFile('photo')) {
            $this->fileService->storeInDatabase($request->file('photo'), 'photo_path', $pointage, 'ID');
        }

        return redirect()->back()->with('success', __('Pointage modifié avec succès'));
    }

    /* =========================================================
       DESTROY
       ========================================================= */

    public function destroy($id)
    {
        $pointage = $this->repository->findById($id);

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Pointage',
            modelId: (int) $id,
            modelLabel: "Employé #{$pointage->employee_id} — {$pointage->type_} à " . \Carbon\Carbon::parse($pointage->timestamp_)->format('d/m/Y H:i'),
        );

        $this->repository->delete($id);

        return redirect()->back()->with('success', __('Pointage supprimé avec succès'));
    }

    /* =========================================================
       EXPORTS (bloqués pour Supervisor par le middleware)
       ========================================================= */

    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method',
            'date_from', 'date_to'
        ]);

        $pointages = $this->repository->getAllForExport($filters);

        ActivityLogService::log(action: 'export_excel', modelType: 'Pointage');

        if (!empty($filters['employee_id'])) {
            return $this->exportService->exportToExcel($pointages, __('Pointages'));
        }

        $grouped = $pointages->groupBy('Employé(e)')
            ->map(fn($items) => $items->values()->all())
            ->all();

        return $this->exportService->exportToExcelZip($grouped, __('Pointages'));
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'employee_id', 'SiegeID', 'type_', 'auth_method',
            'date_from', 'date_to'
        ]);

        $pointages = $this->repository->getAllForExport($filters);

        ActivityLogService::log(action: 'export_pdf', modelType: 'Pointage');

        if (!empty($filters['employee_id'])) {
            return $this->exportService->exportToPdf($pointages, 'Pointages', 'exports.generic');
        }

        $grouped = $pointages->groupBy('Employé(e)')
            ->map(fn($items) => $items->values()->all())
            ->all();

        return $this->exportService->exportToPdfZip($grouped, 'Pointages', 'exports.generic');
    }

    /* =========================================================
       PHOTO
       ========================================================= */

    public function getPhoto($id)
    {
        $pointage = Pointage::findOrFail($id);

        // ✅ Filtre Supervisor
        $this->ensurePointageAccessible($pointage);

        if (!$pointage->photo_path) {
            $message = 'Face non trouvée';
            return view('404', compact('message'));
        }

        $imageData = $this->fileService->retrieveFromDatabase($pointage, 'photo_path');

        return response($imageData)->header('Content-Type', 'image/png');
    }

    public function getPhotoThumbnail($id)
    {
        $pointage = Pointage::findOrFail($id);

        // ✅ Filtre Supervisor
        $this->ensurePointageAccessible($pointage);

        if (!$pointage->photo_path) {
            $message = 'Face non trouvée';
            return view('404', compact('message'));
        }

        $imageData = $this->fileService->retrieveFromDatabase($pointage, 'photo_path');
        $thumbnail = $this->fileService->createThumbnail($imageData);

        return response($thumbnail)->header('Content-Type', 'image/png');
    }

    /* =========================================================
       AJAX : Employés par siège
       ========================================================= */

    public function getEmployesBySiege($SiegeID)
    {
        if (!$SiegeID) {
            return response()->json([]);
        }

        $user = auth()->user();

        $query = Employe::where('SiegeID', $SiegeID)
            ->where('Actived', 1);

        // ✅ Filtre Supervisor : uniquement ses employés
        if ($user->isSupervisor()) {
            $accessibleIds = $this->getAccessibleEmployeeIds($user);
            $query->whereIn('ID', $accessibleIds);
        }

        $employes = $query->get(['ID', 'Nom', 'BadgeID']);

        return response()->json($employes);
    }
}