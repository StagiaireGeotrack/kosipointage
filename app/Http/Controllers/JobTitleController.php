<?php
// app/Http/Controllers/JobTitleController.php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use App\Models\HierarchyLevel;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class JobTitleController extends Controller
{
    /**
     * Affiche la liste des postes
     */
    public function index(Request $request)
    {
        $query = JobTitle::with(['hierarchyLevel', 'employes']);

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par niveau KOSI
        if ($request->filled('hierarchy_level_id')) {
            $query->where('hierarchy_level_id', $request->hierarchy_level_id);
        }

        $jobTitles = $query->orderBy('name')->paginate(15);
        
        // Récupérer tous les niveaux pour le filtre
        $levels = HierarchyLevel::orderBy('rank')->get();

        return view('job_titles.index', compact('jobTitles', 'levels'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $levels = HierarchyLevel::orderBy('rank')->get();
        return view('job_titles.create', compact('levels'));
    }

    /**
     * Enregistre un nouveau poste
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:job_titles,code',
            'hierarchy_level_id' => 'nullable|exists:hierarchy_levels,id',
        ]);

        $jobTitle = JobTitle::create($validated);

        // ✅ CORRIGÉ : 4ème paramètre = chaîne de caractères
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,  // ← CHAÎNE, pas un tableau !
                'Poste créé : ' . $jobTitle->name . ' (' . ($jobTitle->code ?? 'sans code') . ')'
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste créé avec succès.');
    }

    /**
     * Affiche les détails d'un poste
     */
    public function show(JobTitle $jobTitle)
    {
        $jobTitle->load(['hierarchyLevel', 'employes']);
        return view('job_titles.show', compact('jobTitle'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(JobTitle $jobTitle)
    {
        $levels = HierarchyLevel::orderBy('rank')->get();
        return view('job_titles.edit', compact('jobTitle', 'levels'));
    }

    /**
     * Met à jour un poste
     */
    public function update(Request $request, JobTitle $jobTitle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:job_titles,code,' . $jobTitle->id,
            'hierarchy_level_id' => 'nullable|exists:hierarchy_levels,id',
        ]);

        $jobTitle->update($validated);

        // ✅ CORRIGÉ : 4ème paramètre = chaîne de caractères
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,  // ← CHAÎNE, pas un tableau !
                'Poste mis à jour : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste mis à jour avec succès.');
    }

    /**
     * Supprime un poste (soft delete)
     */
    public function destroy(JobTitle $jobTitle)
    {
        if ($jobTitle->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés ont ce poste.');
        }

        $jobTitle->delete();

        // ✅ CORRIGÉ : 4ème paramètre = chaîne de caractères
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,  // ← CHAÎNE, pas un tableau !
                'Poste supprimé : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste supprimé avec succès.');
    }

    /**
     * Restaure un poste supprimé
     */
    public function restore($id)
    {
        $jobTitle = JobTitle::withTrashed()->findOrFail($id);
        $jobTitle->restore();

        // ✅ CORRIGÉ : 4ème paramètre = chaîne de caractères
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'restore',
                'JobTitle',
                $jobTitle->id,
                $jobTitle->name,  // ← CHAÎNE, pas un tableau !
                'Poste restauré : ' . $jobTitle->name
            );
        }

        return redirect()->route('admin.job-titles.index')
            ->with('success', 'Poste restauré avec succès.');
    }
}