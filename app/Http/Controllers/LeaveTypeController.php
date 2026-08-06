<?php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    private function isSuperAdmin(): bool
    {
        $user = auth()->user();
        return $user instanceof \App\Models\Administration && ($user->IsSuperAdmin || is_null($user->SiegeID));
    }

    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->paginate(20);
        return view('conges.leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        $companies = $this->isSuperAdmin() 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : collect();

        return view('conges.leave_types.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $this->isSuperAdmin();

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($request, $isSuperAdmin, $user) {
                    $companyId = $isSuperAdmin ? $request->input('company_id') : $user->SiegeID;
                    // Si vide ou null, on force null pour l'unicité
                    $query->where('company_id', $companyId ?: null);
                })
            ],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('leave_types')->where(function ($query) use ($request, $isSuperAdmin, $user) {
                    $companyId = $isSuperAdmin ? $request->input('company_id') : $user->SiegeID;
                    $query->where('company_id', $companyId ?: null);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        // Détermine company_id
        if ($isSuperAdmin) {
            $validated['company_id'] = $request->filled('company_id') ? $request->company_id : null;
        } else {
            $validated['company_id'] = $user->SiegeID;
        }

        $validated['created_by'] = $user->id;
        $validated['is_active'] = $request->boolean('is_active', true);

        LeaveType::create($validated);

        return redirect()->route('leave-types.index')
            ->with('success', 'Type de congé créé avec succès.');
    }

    public function edit(LeaveType $leaveType)
    {
        $companies = $this->isSuperAdmin() 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : collect();

        return view('conges.leave_types.edit', compact('leaveType', 'companies'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $user = auth()->user();
        $isSuperAdmin = $this->isSuperAdmin();

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($request, $isSuperAdmin, $user, $leaveType) {
                    $companyId = $isSuperAdmin ? $request->input('company_id') : $user->SiegeID;
                    $query->where('company_id', $companyId ?: null);
                })->ignore($leaveType->id)
            ],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('leave_types')->where(function ($query) use ($request, $isSuperAdmin, $user, $leaveType) {
                    $companyId = $isSuperAdmin ? $request->input('company_id') : $user->SiegeID;
                    $query->where('company_id', $companyId ?: null);
                })->ignore($leaveType->id)
            ],
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        if ($isSuperAdmin) {
            $validated['company_id'] = $request->filled('company_id') ? $request->company_id : null;
        } else {
            $validated['company_id'] = $user->SiegeID;
        }

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