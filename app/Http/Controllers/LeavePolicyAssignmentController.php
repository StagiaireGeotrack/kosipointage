<?php
// app/Http/Controllers/LeavePolicyAssignmentController.php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use App\Models\LeavePolicyAssignment;
use App\Models\Department;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeavePolicyAssignmentController extends Controller
{
    /**
     * ✅ Vérification : tout le monde connecté peut accéder (pour tester)
     * ⚠️ À sécuriser après test
     */
    private function checkAccess(): void
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Vous devez être connecté.');
        }
        
        // ✅ Autoriser TOUT LE MONDE pour le moment (test)
        // À remplacer par une vraie vérification après
        return;
        
        // OU pour une vraie vérification :
        // $isSuperAdmin = isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1;
        // $isSeller = isset($user->IsSeller) && $user->IsSeller == 1;
        // $isManager = isset($user->IsManager) && $user->IsManager == 1;
        // 
        // if (!$isSuperAdmin && !$isSeller && !$isManager) {
        //     abort(403, 'Vous n\'êtes pas autorisé.');
        // }
    }

    public function index(Request $request)
    {
        $this->checkAccess();

        $query = LeavePolicyAssignment::with(['leavePolicy', 'employee', 'department', 'site'])
            ->orderBy('priority', 'desc');

        if ($request->filled('leave_policy_id')) {
            $query->where('leave_policy_id', $request->leave_policy_id);
        }

        $assignments = $query->paginate(15);
        $leavePolicies = LeavePolicy::where('is_active', true)->orderBy('name')->get();

        return view('leave_policy_assignments.index', compact('assignments', 'leavePolicies'));
    }

    public function create()
    {
        $this->checkAccess();

        $leavePolicies = LeavePolicy::where('is_active', true)->orderBy('name')->get();
        $employees = Employe::where('Actived', 1)->where('deleted', 0)->orderBy('Nom')->get();
        $departments = Department::orderBy('name')->get();
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('leave_policy_assignments.create', compact(
            'leavePolicies', 'employees', 'departments', 'sites'
        ));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'leave_policy_id' => 'required|exists:leave_policies,id',
            'assignment_type' => 'required|in:individual,department,site,company',
            'target_id' => 'required|integer',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $targetColumn = match($validated['assignment_type']) {
            'individual' => 'employee_id',
            'department' => 'department_id',
            'site' => 'site_id',
            'company' => 'company_id',
        };

        $data = [
            'leave_policy_id' => $validated['leave_policy_id'],
            $targetColumn => $validated['target_id'],
            'assignment_type' => $validated['assignment_type'],
            'priority' => $validated['priority'] ?? 50,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Ajouter le site_id de l'utilisateur connecté
        $user = Auth::user();
        if ($user && isset($user->SiegeID) && $user->SiegeID) {
            $data['site_id'] = $user->SiegeID;
        }

        $assignment = LeavePolicyAssignment::create($data);

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Assignation créée avec succès.');
    }

    public function show(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();
        return view('leave_policy_assignments.show', compact('leavePolicyAssignment'));
    }

    public function edit(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();

        $leavePolicies = LeavePolicy::where('is_active', true)->orderBy('name')->get();
        $employees = Employe::where('Actived', 1)->where('deleted', 0)->orderBy('Nom')->get();
        $departments = Department::orderBy('name')->get();
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('leave_policy_assignments.edit', compact(
            'leavePolicyAssignment', 'leavePolicies', 'employees', 'departments', 'sites'
        ));
    }

    public function update(Request $request, LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'leave_policy_id' => 'required|exists:leave_policies,id',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $leavePolicyAssignment->update([
            'leave_policy_id' => $validated['leave_policy_id'],
            'priority' => $validated['priority'] ?? 50,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Assignation mise à jour avec succès.');
    }

    public function toggle($id)
    {
        $this->checkAccess();

        $assignment = LeavePolicyAssignment::findOrFail($id);
        $assignment->is_active = !$assignment->is_active;
        $assignment->save();

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Statut modifié avec succès.');
    }

    public function destroy(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();
        $leavePolicyAssignment->delete();

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Assignation supprimée avec succès.');
    }
}