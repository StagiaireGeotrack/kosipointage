<?php
// app/Http/Controllers/HierarchyLevelController.php

namespace App\Http\Controllers;

use App\Models\HierarchyLevel;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class HierarchyLevelController extends Controller
{
    /**
     * Affiche la liste des niveaux hiérarchiques
     */
    public function index(Request $request)
    {
        $query = HierarchyLevel::with(['employees']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('managerial')) {
            $query->where('is_managerial', $request->managerial);
        }

        $levels = $query->orderBy('rank')->paginate(15);

        // CHEMIN CORRIGÉ : sans admin.
        return view('hierarchy_levels.index', compact('levels'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        // CHEMIN CORRIGÉ : sans admin.
        return view('hierarchy_levels.create');
    }

    /**
     * Enregistre un nouveau niveau
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:10|unique:hierarchy_levels,code',
            'rank' => 'required|integer|min:0',
            'is_managerial' => 'nullable|boolean',
        ]);

        $validated['company_id'] = 0;
        $validated['is_managerial'] = $request->boolean('is_managerial', false);

        $level = HierarchyLevel::create($validated);

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',
                'HierarchyLevel',
                $level->id,
                ['name' => $level->name, 'code' => $level->code]
            );
        }

        // CHEMIN CORRIGÉ : sans admin.
        return redirect()->route('hierarchy-levels.index')
            ->with('success', 'Niveau hiérarchique créé avec succès.');
    }

    /**
     * Affiche les détails d'un niveau
     */
    public function show(HierarchyLevel $hierarchyLevel)
    {
        $hierarchyLevel->load(['employees']);
        // CHEMIN CORRIGÉ : sans admin.
        return view('hierarchy_levels.show', compact('hierarchyLevel'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(HierarchyLevel $hierarchyLevel)
    {
        // CHEMIN CORRIGÉ : sans admin.
        return view('hierarchy_levels.edit', compact('hierarchyLevel'));
    }

    /**
     * Met à jour un niveau
     */
    public function update(Request $request, HierarchyLevel $hierarchyLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:10|unique:hierarchy_levels,code,' . $hierarchyLevel->id,
            'rank' => 'required|integer|min:0',
            'is_managerial' => 'nullable|boolean',
        ]);

        $validated['is_managerial'] = $request->boolean('is_managerial', false);

        $hierarchyLevel->update($validated);

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',
                'HierarchyLevel',
                $hierarchyLevel->id,
                ['name' => $hierarchyLevel->name, 'code' => $hierarchyLevel->code]
            );
        }

        // CHEMIN CORRIGÉ : sans admin.
        return redirect()->route('hierarchy-levels.index')
            ->with('success', 'Niveau hiérarchique mis à jour avec succès.');
    }

    /**
     * Supprime un niveau (soft delete)
     */
    public function destroy(HierarchyLevel $hierarchyLevel)
    {
        if ($hierarchyLevel->employees()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés ont ce niveau.');
        }

        $hierarchyLevel->delete();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',
                'HierarchyLevel',
                $hierarchyLevel->id,
                ['name' => $hierarchyLevel->name, 'code' => $hierarchyLevel->code]
            );
        }

        // CHEMIN CORRIGÉ : sans admin.
        return redirect()->route('hierarchy-levels.index')
            ->with('success', 'Niveau hiérarchique supprimé avec succès.');
    }

    /**
     * Restaure un niveau supprimé
     */
    public function restore($id)
    {
        $level = HierarchyLevel::withTrashed()->findOrFail($id);
        $level->restore();

        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'restore',
                'HierarchyLevel',
                $level->id,
                ['name' => $level->name, 'code' => $level->code]
            );
        }

        // CHEMIN CORRIGÉ : sans admin.
        return redirect()->route('hierarchy-levels.index')
            ->with('success', 'Niveau hiérarchique restauré avec succès.');
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $query = HierarchyLevel::with(['employees']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $levels = $query->get();

        return back()->with('info', 'Export Excel en cours de développement.');
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = HierarchyLevel::with(['employees']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $levels = $query->get();

        return back()->with('info', 'Export PDF en cours de développement.');
    }
}