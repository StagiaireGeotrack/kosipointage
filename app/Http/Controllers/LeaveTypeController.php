<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->paginate(20);
        return view('conges.leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('conges.leave_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'code' => 'required|string|max:50|unique:leave_types,code',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        LeaveType::create($validated);

        return redirect()->route('leave-types.index')
            ->with('success', 'Type de congé créé avec succès.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('conges.leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'code' => 'required|string|max:50|unique:leave_types,code,' . $leaveType->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $leaveType->update($validated);

        return redirect()->route('leave-types.index')
            ->with('success', 'Type de congé mis à jour avec succès.');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->leavePolicies()->exists()) {
            return redirect()->route('leave-types.index')
                ->with('error', 'Impossible de supprimer ce type : il est utilisé par des règles de siège.');
        }

        $leaveType->delete();

        return redirect()->route('leave-types.index')
            ->with('success', 'Type de congé supprimé avec succès.');
    }
}