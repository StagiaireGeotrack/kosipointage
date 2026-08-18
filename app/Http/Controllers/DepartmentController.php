<?php
// app/Http/Controllers/DepartmentController.php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EntrepriseSiege;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class DepartmentController extends Controller
{
    /**
     * Affiche la liste des services
     */
    public function index(Request $request)
    {
        $query = Department::with(['site', 'managerEmployee', 'employes']);

        // Filtre par siège
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $departments = $query->orderBy('name')->paginate(15);
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('departments.index', compact('departments', 'sites'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $sites = EntrepriseSiege::orderBy('Nom')->get();
        
        $adminSiegeId = auth()->user()->SiegeID ?? null;
        if ($adminSiegeId) {
            $employes = Employe::where('SiegeID', $adminSiegeId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();
        } else {
            $employes = collect();
        }

        return view('departments.create', compact('sites', 'employes'));
    }

    /**
     * Enregistre un nouveau service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'manager_employee_id' => 'nullable|exists:employes,ID',
        ]);

        // Vérifier si le code est unique par siège
        if ($request->filled('code')) {
            $exists = Department::where('site_id', $request->site_id)
                ->where('code', $request->code)
                ->exists();
            if ($exists) {
                return back()
                    ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                    ->withInput();
            }
        }

        $department = Department::create($validated);

        // Log simplifié
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',
                'Department',
                $department->id,
                ['name' => $department->name]
            );
        }

        return redirect()->route('departments.index')
            ->with('success', 'Service créé avec succès.');
    }

    /**
     * Affiche les détails d'un service
     */
    public function show(Department $department)
    {
        $department->load(['site', 'managerEmployee', 'employes']);
        return view('departments.show', compact('department'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(Department $department)
    {
        $sites = EntrepriseSiege::orderBy('Nom')->get();
        $employes = Employe::where('SiegeID', $department->site_id)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        return view('departments.edit', compact('department', 'sites', 'employes'));
    }

    /**
     * Met à jour un service
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'manager_employee_id' => 'nullable|exists:employes,ID',
        ]);

        // Vérifier si le code est unique par siège
        if ($request->filled('code')) {
            $exists = Department::where('site_id', $request->site_id)
                ->where('code', $request->code)
                ->where('id', '!=', $department->id)
                ->exists();
            if ($exists) {
                return back()
                    ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                    ->withInput();
            }
        }

        $department->update($validated);

        // Log simplifié
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',
                'Department',
                $department->id,
                ['name' => $department->name]
            );
        }

        return redirect()->route('departments.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    /**
     * Supprime un service (soft delete)
     */
    public function destroy(Department $department)
    {
        if ($department->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés sont encore dans ce service.');
        }

        $department->delete();

        // Log simplifié
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',
                'Department',
                $department->id,
                ['name' => $department->name]
            );
        }

        return redirect()->route('departments.index')
            ->with('success', 'Service supprimé avec succès.');
    }

    /**
     * Restaure un service supprimé
     */
    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->restore();

        // Log simplifié
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'restore',
                'Department',
                $department->id,
                ['name' => $department->name]
            );
        }

        return redirect()->route('departments.index')
            ->with('success', 'Service restauré avec succès.');
    }
    

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Department::with(['site', 'managerEmployee', 'employes']);
        
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $departments = $query->get();

        // Logique d'export à implémenter
        return back()->with('info', 'Export Excel en cours de développement.');
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Department::with(['site', 'managerEmployee', 'employes']);
        
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $departments = $query->get();

        // Logique d'export à implémenter
        return back()->with('info', 'Export PDF en cours de développement.');
    }
}