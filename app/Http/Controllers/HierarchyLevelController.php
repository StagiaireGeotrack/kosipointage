<?php

namespace App\Http\Controllers;

use App\Models\HierarchyLevel;
use Illuminate\Http\Request;

class HierarchyLevelController extends Controller
{
    public function index()
    {
        $levels = HierarchyLevel::orderBy('rank', 'desc')->paginate(20);
        return view('hierarchy_levels.index', compact('levels'));
    }

    public function create()
    {
        return view('hierarchy_levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'rank' => 'required|integer|min:1|max:100',
            'is_managerial' => 'boolean',
        ]);

        HierarchyLevel::create($validated + ['company_id' => 0, 'is_managerial' => $request->boolean('is_managerial')]);

        return redirect()->route('hierarchy-levels.index')->with('success', 'Niveau créé.');
    }

    public function edit(HierarchyLevel $hierarchyLevel)
    {
        return view('hierarchy_levels.edit', compact('hierarchyLevel'));
    }

    public function update(Request $request, HierarchyLevel $hierarchyLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'rank' => 'required|integer|min:1|max:100',
            'is_managerial' => 'boolean',
        ]);

        $hierarchyLevel->update($validated + ['is_managerial' => $request->boolean('is_managerial')]);

        return redirect()->route('hierarchy-levels.index')->with('success', 'Niveau mis à jour.');
    }

    public function destroy(HierarchyLevel $hierarchyLevel)
    {
        if ($hierarchyLevel->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés ont ce niveau.');
        }

        $hierarchyLevel->delete();
        return redirect()->route('hierarchy-levels.index')->with('success', 'Niveau supprimé.');
    }
}