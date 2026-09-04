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
     * ✅ Vérification des droits avec multi-tenant
     */
    private function checkAccess(): void
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Vous devez être connecté.');
        }
        
        // Vérifier que l'utilisateur a accès (SuperAdmin ou Manager)
        $isSuperAdmin = isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1;
        $isManager = isset($user->IsManager) && $user->IsManager == 1;
        
        if (!$isSuperAdmin && !$isManager) {
            abort(403, 'Vous n\'êtes pas autorisé.');
        }
    }

    /**
     * ✅ Récupérer le site de l'utilisateur connecté
     */
    private function getUserSiteId(): ?int
    {
        $user = Auth::user();
        return $user ? $user->SiegeID : null;
    }

    /**
     * ✅ Vérifier si l'utilisateur est SuperAdmin
     */
    private function isSuperAdmin(): bool
    {
        $user = Auth::user();
        return $user && isset($user->IsSuperAdmin) && $user->IsSuperAdmin == 1;
    }

    public function index(Request $request)
    {
        $this->checkAccess();

        $userSiteId = $this->getUserSiteId();
        $isSuperAdmin = $this->isSuperAdmin();

        $query = LeavePolicyAssignment::with(['leavePolicy', 'employee', 'department', 'site'])
            ->orderBy('priority', 'desc');

        // ✅ Filtrer par site pour les non-superadmins
        if (!$isSuperAdmin && $userSiteId) {
            $query->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            });
        }

        // Filtre par politique
        if ($request->filled('leave_policy_id')) {
            $query->where('leave_policy_id', $request->leave_policy_id);
        }

        // Filtre par site (admin)
        if ($request->filled('site_id') && $isSuperAdmin) {
            $query->where('site_id', $request->site_id);
        }

        $assignments = $query->paginate(15);
        $leavePolicies = LeavePolicy::where('is_active', true)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where(function ($sub) use ($userSiteId) {
                    $sub->where('site_id', $userSiteId)
                        ->orWhereNull('site_id');
                });
            })
            ->orderBy('name')
            ->get();

        $sites = $isSuperAdmin 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : EntrepriseSiege::where('ID', $userSiteId)->orderBy('Nom')->get();

        return view('leave_policy_assignments.index', compact('assignments', 'leavePolicies', 'sites'));
    }

    public function create()
    {
        $this->checkAccess();

        $userSiteId = $this->getUserSiteId();
        $isSuperAdmin = $this->isSuperAdmin();

        // ✅ Filtrer les politiques par site
        $leavePolicies = LeavePolicy::where('is_active', true)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where(function ($sub) use ($userSiteId) {
                    $sub->where('site_id', $userSiteId)
                        ->orWhereNull('site_id');
                });
            })
            ->orderBy('name')
            ->get();

        // ✅ Filtrer les employés par site
        $employees = Employe::where('Actived', 1)
            ->where('deleted', 0)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where('SiegeID', $userSiteId);
            })
            ->orderBy('Nom')
            ->get();

        // ✅ Filtrer les départements par site
        $departments = Department::orderBy('name')
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where('site_id', $userSiteId);
            })
            ->get();

        // ✅ Filtrer les sites
        $sites = $isSuperAdmin 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : EntrepriseSiege::where('ID', $userSiteId)->orderBy('Nom')->get();

        return view('leave_policy_assignments.create', compact(
            'leavePolicies', 'employees', 'departments', 'sites'
        ));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $userSiteId = $this->getUserSiteId();
        $isSuperAdmin = $this->isSuperAdmin();

        $validated = $request->validate([
            'leave_policy_id' => 'required|exists:leave_policies,id',
            'assignment_type' => 'required|in:individual,department,site,company,global',
            'target_id' => 'nullable|integer',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
            'site_id' => 'nullable|exists:entreprises_sieges,ID',
        ]);

        // ✅ Déterminer le site de l'assignation
        $siteId = null;
        if ($isSuperAdmin && $request->filled('site_id')) {
            $siteId = $request->site_id;
        } elseif (!$isSuperAdmin && $userSiteId) {
            $siteId = $userSiteId;
        }

        // ✅ Pour les assignations "global", on force site_id = null
        if ($validated['assignment_type'] === 'global') {
            $siteId = null;
        }

        // ✅ Construire les données
        $data = [
            'leave_policy_id' => $validated['leave_policy_id'],
            'assignment_type' => $validated['assignment_type'],
            'priority' => $validated['priority'] ?? 50,
            'is_active' => $request->boolean('is_active', true),
            'site_id' => $siteId,
        ];

        // ✅ Ajouter la cible selon le type
        $targetColumn = match($validated['assignment_type']) {
            'individual' => 'employee_id',
            'department' => 'department_id',
            'site' => 'site_id',
            'company' => 'company_id',
            'global' => null,
        };

        if ($targetColumn && $request->filled('target_id')) {
            $data[$targetColumn] = $request->target_id;
        }

        // ✅ Vérification des doublons
        $exists = LeavePolicyAssignment::where('leave_policy_id', $data['leave_policy_id'])
            ->where('assignment_type', $data['assignment_type'])
            ->when($targetColumn && isset($data[$targetColumn]), function ($q) use ($targetColumn, $data) {
                return $q->where($targetColumn, $data[$targetColumn]);
            })
            ->when($data['site_id'], function ($q) use ($data) {
                return $q->where('site_id', $data['site_id']);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['target_id' => 'Cette assignation existe déjà.'])->withInput();
        }

        $assignment = LeavePolicyAssignment::create($data);

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Assignation créée avec succès.');
    }

    public function show(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();
        
        // ✅ Vérifier l'accès au site
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leavePolicyAssignment->site_id && $leavePolicyAssignment->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à cette assignation.');
        }

        return view('leave_policy_assignments.show', compact('leavePolicyAssignment'));
    }

    public function edit(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();

        // ✅ Vérifier l'accès au site
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leavePolicyAssignment->site_id && $leavePolicyAssignment->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à cette assignation.');
        }

        $isSuperAdmin = $this->isSuperAdmin();

        // ✅ Filtrer les politiques par site
        $leavePolicies = LeavePolicy::where('is_active', true)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where(function ($sub) use ($userSiteId) {
                    $sub->where('site_id', $userSiteId)
                        ->orWhereNull('site_id');
                });
            })
            ->orderBy('name')
            ->get();

        // ✅ Filtrer les employés par site
        $employees = Employe::where('Actived', 1)
            ->where('deleted', 0)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where('SiegeID', $userSiteId);
            })
            ->orderBy('Nom')
            ->get();

        // ✅ Filtrer les départements par site
        $departments = Department::orderBy('name')
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where('site_id', $userSiteId);
            })
            ->get();

        // ✅ Filtrer les sites
        $sites = $isSuperAdmin 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : EntrepriseSiege::where('ID', $userSiteId)->orderBy('Nom')->get();

        return view('leave_policy_assignments.edit', compact(
            'leavePolicyAssignment', 'leavePolicies', 'employees', 'departments', 'sites'
        ));
    }

    public function update(Request $request, LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();

        // ✅ Vérifier l'accès au site
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leavePolicyAssignment->site_id && $leavePolicyAssignment->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à cette assignation.');
        }

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
        
        // ✅ Vérifier l'accès au site
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $assignment->site_id && $assignment->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à cette assignation.');
        }

        $assignment->is_active = !$assignment->is_active;
        $assignment->save();

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Statut modifié avec succès.');
    }

    public function destroy(LeavePolicyAssignment $leavePolicyAssignment)
    {
        $this->checkAccess();

        // ✅ Vérifier l'accès au site
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leavePolicyAssignment->site_id && $leavePolicyAssignment->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à cette assignation.');
        }

        $leavePolicyAssignment->delete();

        return redirect()->route('admin.leave-policy-assignments.index')
            ->with('success', 'Assignation supprimée avec succès.');
    }
}