<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EntrepriseSiege;
use App\Models\Employe;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['site', 'managerEmployee']);

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $departments = $query->orderBy('name')->paginate(20);
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('departments.index', compact('departments', 'sites'));
    }

    public function create()
    {
        $sites = EntrepriseSiege::orderBy('Nom')->get();
        
        // Si l'admin a un siège fixe, on pré-charge ses employés
        $adminSiegeId = auth()->user()->SiegeID ?? null;
        if ($adminSiegeId) {
            $employes = Employe::where('SiegeID', $adminSiegeId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();
        } else {
            $employes = collect(); // vide par défaut, se remplira par AJAX
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

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Service créé.');
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

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Department $department)
    {
        if ($department->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés sont encore dans ce service.');
        }

        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Service supprimé.');
    }
}