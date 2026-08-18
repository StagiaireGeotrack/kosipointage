<?php
// app/Http/Controllers/LeavePolicyAssignmentController.php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use App\Models\LeavePolicyAssignment;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\HierarchyLevel;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class LeavePolicyAssignmentController extends Controller
{
    private function isSuperAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->IsSuperAdmin == 1;
    }

    private function getUserSiteId(): ?int
    {
        $user = Auth::user();
        return $user ? $user->SiegeID : null;
    }

    /**
     * Affiche la liste des assignations
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', LeavePolicyAssignment::class);

        $query = LeavePolicyAssignment::with(['leavePolicy', 'employee', 'department', 'jobTitle', 'hierarchyLevel', 'site'])
            ->orderBy('priority', 'desc');

        if ($request->filled('leave_policy_id')) {
            $query->where('leave_policy_id', $request->leave_policy_id);
        }

        if ($request->filled('target_type')) {
            $targetType = $request->target_type;
            if ($targetType === 'employee') {
                $query->whereNotNull('employee_id');
            } elseif ($targetType === 'department') {
                $query->whereNotNull('department_id');
            } elseif ($targetType === 'job_title') {
                $query->whereNotNull('job_title_id');
            } elseif ($targetType === 'hierarchy_level') {
                $query->whereNotNull('hierarchy_level_id');
            } elseif ($targetType === 'site') {
                $query->whereNotNull('site_id');
            }
        }

        $assignments = $query->paginate(15);

        $leavePolicies = LeavePolicy::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('leave_policy_assignments.index', compact('assignments', 'leavePolicies'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->authorize('create', LeavePolicyAssignment::class);

        $leavePolicies = LeavePolicy::where('is_active', true)
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->get();
        $jobTitles = JobTitle::orderBy('name')->get();
        $hierarchyLevels = HierarchyLevel::orderBy('rank')->get();
        $employees = Employe::where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('leave_policy_assignments.create', compact(
            'leavePolicies',
            'departments',
            'jobTitles',
            'hierarchyLevels',
            'employees',
            'sites'
        ));
    }

    /**
     * Enregistre une nouvelle assignation
     */
    public function store(Request $request)
    {
        $this->authorize('create', LeavePolicyAssignment::class);

        $validated = $request->validate([
            'leave_policy_id' => 'required|exists:leave_policies,id',
            'target_type' => 'required|in:employee,department,job_title,hierarchy_level,site,company',
            'target_id' => 'required|integer',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $targetColumn = match($validated['target_type']) {
            'employee' => 'employee_id',
            'department' => 'department_id',
            'job_title' => 'job_title_id',
            'hierarchy_level' => 'hierarchy_level_id',
            'site' => 'site_id',
            'company' => 'company_id',
        };

        $exists = LeavePolicyAssignment::where($targetColumn, $validated['target_id'])
            ->where('leave_policy_id', $validated['leave_policy_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['target_id' => 'Cette assignation existe déjà pour cette politique et cette cible.'])
                ->withInput();
        }

        $data = [
            'leave_policy_id' => $validated['leave_policy_id'],
            $targetColumn => $validated['target_id'],
            'priority' => $validated['priority'] ?? 50,
            'is_active' => $request->boolean('is_active', true),
        ];

        $assignment = LeavePolicyAssignment::create($data);

        // CORRECTION : Utiliser la bonne signature de ActivityLogService
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'create',                          // action
                'LeavePolicyAssignment',           // modelType
                $assignment->id,                   // modelId
                $assignment->leavePolicy->name ?? 'Assignation', // modelLabel (string)
                'Assignation créée avec succès'    // description
            );
        }

        return redirect()->route('leave-policy-assignments.index')
            ->with('success', 'Assignation créée avec succès.');
    }

    /**
     * Affiche les détails d'une assignation
     */
    public function show(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->authorize('view', $leavePolicyAssignment);
        $leavePolicyAssignment->load(['leavePolicy', 'employee', 'department', 'jobTitle', 'hierarchyLevel', 'site']);
        return view('leave_policy_assignments.show', compact('leavePolicyAssignment'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->authorize('update', $leavePolicyAssignment);

        $leavePolicies = LeavePolicy::where('is_active', true)
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->get();
        $jobTitles = JobTitle::orderBy('name')->get();
        $hierarchyLevels = HierarchyLevel::orderBy('rank')->get();
        $employees = Employe::where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        return view('leave_policy_assignments.edit', compact(
            'leavePolicyAssignment',
            'leavePolicies',
            'departments',
            'jobTitles',
            'hierarchyLevels',
            'employees',
            'sites'
        ));
    }

    /**
     * Met à jour une assignation
     */
    public function update(Request $request, LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->authorize('update', $leavePolicyAssignment);

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

        // CORRECTION : Utiliser la bonne signature de ActivityLogService
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'update',                          // action
                'LeavePolicyAssignment',           // modelType
                $leavePolicyAssignment->id,        // modelId
                $leavePolicyAssignment->leavePolicy->name ?? 'Assignation', // modelLabel (string)
                'Assignation mise à jour'          // description
            );
        }

        return redirect()->route('leave-policy-assignments.index')
            ->with('success', 'Assignation mise à jour avec succès.');
    }

    /**
     * Supprime une assignation
     */
    public function destroy(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->authorize('delete', $leavePolicyAssignment);

        $leavePolicyAssignment->delete();

        // CORRECTION : Utiliser la bonne signature de ActivityLogService
        if (class_exists(ActivityLogService::class)) {
            ActivityLogService::log(
                'delete',                          // action
                'LeavePolicyAssignment',           // modelType
                $leavePolicyAssignment->id,        // modelId
                $leavePolicyAssignment->leavePolicy->name ?? 'Assignation', // modelLabel (string)
                'Assignation supprimée'            // description
            );
        }

        return redirect()->route('leave-policy-assignments.index')
            ->with('success', 'Assignation supprimée avec succès.');
    }
}