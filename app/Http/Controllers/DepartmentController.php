<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EntrepriseSiege;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['site', 'managerEmployee', 'employes']);

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'manager_employee_id' => 'nullable|exists:employes,ID',
        ]);

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

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',
                'Department',
                $department->id,
                $department->name,
                'Service créé : ' . $department->name . ' (' . ($department->code ?? '') . ')'
            );
        }

        return redirect()->route('admin.departments.index')
            ->with('success', 'Service créé avec succès.');
    }

    public function show(Department $department)
    {
        // 🔥 Chargement des postes en plus
        $department->load(['site', 'managerEmployee', 'employes', 'jobTitles']);
        return view('departments.show', compact('department'));
    }

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

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'manager_employee_id' => 'nullable|exists:employes,ID',
        ]);

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

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',
                'Department',
                $department->id,
                $department->name,
                'Service mis à jour : ' . $department->name
            );
        }

        return redirect()->route('admin.departments.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Department $department)
    {
        // 🔥 Empêcher la suppression si des employés OU des postes sont attachés
        if ($department->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés sont encore dans ce service.');
        }
        if ($department->jobTitles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des postes sont rattachés à ce service.');
        }

        $department->delete();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',
                'Department',
                $department->id,
                $department->name,
                'Service supprimé : ' . $department->name
            );
        }

        return redirect()->route('admin.departments.index')
            ->with('success', 'Service supprimé avec succès.');
    }

    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->restore();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'restore',
                'Department',
                $department->id,
                $department->name,
                'Service restauré : ' . $department->name
            );
        }

        return redirect()->route('admin.departments.index')
            ->with('success', 'Service restauré avec succès.');
    }

    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Export Excel en cours de développement.');
    }

    public function exportPdf(Request $request)
    {
        return back()->with('info', 'Export PDF en cours de développement.');
    }
}
