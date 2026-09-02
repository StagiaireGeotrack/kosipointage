<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use App\Models\HierarchyLevel;
use App\Models\Department;          // ← AJOUT
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class JobTitleController extends Controller
{
    public function index(Request $request)
    {
        $query = JobTitle::with(['hierarchyLevel', 'department', 'employes']);  // ← AJOUT 'department'

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('hierarchy_level_id')) {
            $query->where('hierarchy_level_id', $request->hierarchy_level_id);
        }

        // 🔥 Filtre par service
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $jobTitles = $query->orderBy('name')->paginate(15);
        $levels = HierarchyLevel::orderBy('rank')->get();

        // 🔥 Récupérer les services pour le filtre (avec scope multi-tenant)
        $departments = Department::orderBy('name')->get();

        return view('job_titles.index', compact('jobTitles', 'levels', 'departments'));
    }

    public function create()
    {
        $levels = HierarchyLevel::orderBy('rank')->get();
        // 🔥 Récupérer les services disponibles (scope multi-tenant automatique)
        $departments = Department::orderBy('name')->get();

        return view('job_titles.create', compact('levels', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:job_titles,code',
            'hierarchy_level_id' => 'nullable|exists:hierarchy_levels,id',
            'department_id' => 'nullable|exists:departments,id',  // 🔥 AJOUT
        ]);

        $jobTitle = JobTitle::create($validated);

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,
                'Poste créé : ' . $jobTitle->name . ' (' . ($jobTitle->code ?? 'sans code') . ')'
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste créé avec succès.');
    }

    public function show(JobTitle $jobTitle)
    {
        // 🔥 Charger la relation department
        $jobTitle->load(['hierarchyLevel', 'department', 'employes']);
        return view('job_titles.show', compact('jobTitle'));
    }

    public function edit(JobTitle $jobTitle)
    {
        $levels = HierarchyLevel::orderBy('rank')->get();
        // 🔥 Récupérer les services
        $departments = Department::orderBy('name')->get();

        return view('job_titles.edit', compact('jobTitle', 'levels', 'departments'));
    }

    public function update(Request $request, JobTitle $jobTitle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:job_titles,code,' . $jobTitle->id,
            'hierarchy_level_id' => 'nullable|exists:hierarchy_levels,id',
            'department_id' => 'nullable|exists:departments,id',  // 🔥 AJOUT
        ]);

        $jobTitle->update($validated);

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,
                'Poste mis à jour : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste mis à jour avec succès.');
    }

    public function destroy(JobTitle $jobTitle)
    {
        if ($jobTitle->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés ont ce poste.');
        }

        $jobTitle->delete();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,
                'Poste supprimé : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste supprimé avec succès.');
    }

    public function restore($id)
    {
        $jobTitle = JobTitle::withTrashed()->findOrFail($id);
        $jobTitle->restore();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'restore',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,
                'Poste restauré : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste restauré avec succès.');
    }
}