<?php

namespace App\Http\Controllers;

use App\Models\LeaveRole;
use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRoleController extends Controller
{
    /**
     * Vérifier si l'utilisateur peut gérer les rôles
     * - SuperAdmin (IsSuperAdmin = 1) : peut tout faire
     * - Admin de siège (IsManager = 1) : peut gérer les rôles de son site
     * - Vendeur (IsSeller = 1) : accès refusé
     */
    private function canManageRoles(): bool
    {
        $user = Auth::user();
        return $user && ($user->IsSuperAdmin == 1 || $user->IsManager == 1);
    }

    /**
     * Vérifier si l'utilisateur est SuperAdmin
     */
    private function isSuperAdmin(): bool
    {
        return Auth::user() && Auth::user()->IsSuperAdmin == 1;
    }

    /**
     * Récupérer l'ID du site de l'utilisateur connecté
     */
    private function getUserSiteId(): ?int
    {
        return Auth::user() ? Auth::user()->SiegeID : null;
    }

    // ============================================
    // INDEX - Liste des rôles (multi-tenant)
    // ============================================
    public function index()
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        $user = Auth::user();
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $query = LeaveRole::query();

        if (!$isSuperAdmin) {
            // L'admin de siège voit :
            // - Les rôles globaux (site_id = NULL)
            // - Les rôles de son site (site_id = $userSiteId)
            $query->where(function ($q) use ($userSiteId) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $userSiteId);
            });
        }

        $roles = $query->orderBy('name')->get();

        return view('leave_roles.index', compact('roles'));
    }

    // ============================================
    // CREATE - Formulaire de création
    // ============================================
    public function create()
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        $sites = $this->isSuperAdmin() ? EntrepriseSiege::orderBy('Nom')->get() : collect();

        return view('leave_roles.create', compact('sites'));
    }

    // ============================================
    // STORE - Créer un rôle
    // ============================================
    public function store(Request $request)
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        $isSuperAdmin = $this->isSuperAdmin();

        // Règles de validation
        $rules = [
            'name' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ];

        if ($isSuperAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Déterminer le site_id
        if (!$isSuperAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $request->input('site_id') ?: null;
        }

        // Vérifier l'unicité (name + site_id)
        $exists = LeaveRole::where('name', $validated['name'])
            ->where(function ($q) use ($validated) {
                $q->where('site_id', $validated['site_id'])
                  ->orWhereNull('site_id');
            })
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Ce nom de rôle existe déjà pour ce site.'])
                ->withInput();
        }

        LeaveRole::create($validated);

        return redirect()->route('admin.leave-roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    // ============================================
    // EDIT - Formulaire d'édition
    // ============================================
    public function edit(LeaveRole $leaveRole)
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        // Vérifier les droits d'accès au rôle
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leaveRole->site_id !== null && $leaveRole->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à ce rôle.');
        }

        $sites = $this->isSuperAdmin() ? EntrepriseSiege::orderBy('Nom')->get() : collect();

        return view('leave_roles.edit', compact('leaveRole', 'sites'));
    }

    // ============================================
    // UPDATE - Mettre à jour un rôle
    // ============================================
    public function update(Request $request, LeaveRole $leaveRole)
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        // Vérifier les droits d'accès au rôle
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leaveRole->site_id !== null && $leaveRole->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à ce rôle.');
        }

        $isSuperAdmin = $this->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ];

        if ($isSuperAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!$isSuperAdmin) {
            $validated['site_id'] = $leaveRole->site_id; // conserver le site existant
        } else {
            $validated['site_id'] = $request->input('site_id') ?: null;
        }

        // Vérifier l'unicité (excluant le rôle actuel)
        $exists = LeaveRole::where('name', $validated['name'])
            ->where('id', '!=', $leaveRole->id)
            ->where(function ($q) use ($validated) {
                $q->where('site_id', $validated['site_id'])
                  ->orWhereNull('site_id');
            })
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Ce nom de rôle existe déjà pour ce site.'])
                ->withInput();
        }

        $leaveRole->update($validated);

        return redirect()->route('admin.leave-roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    // ============================================
    // DESTROY - Supprimer un rôle
    // ============================================
    public function destroy(LeaveRole $leaveRole)
    {
        if (!$this->canManageRoles()) {
            abort(403);
        }

        // Vérifier les droits d'accès au rôle
        $userSiteId = $this->getUserSiteId();
        if (!$this->isSuperAdmin() && $leaveRole->site_id !== null && $leaveRole->site_id != $userSiteId) {
            abort(403, 'Vous n\'avez pas accès à ce rôle.');
        }

        // 🔒 Restriction : Seul le SuperAdmin peut supprimer les rôles critiques
        $criticalRoles = ['manager', 'rh', 'drh', 'direction'];
        if (!$this->isSuperAdmin() && in_array($leaveRole->name, $criticalRoles)) {
            return back()->with('error', 'Vous ne pouvez pas supprimer un rôle critique (' . $leaveRole->label . '). Contactez le SuperAdmin.');
        }

        // Vérifier si le rôle est utilisé dans des workflows ou validateurs
        $usedInWorkflows = \App\Models\LeaveWorkflow::where('steps', 'LIKE', '%"' . $leaveRole->name . '"%')->exists();
        $usedInValidators = \App\Models\LeaveValidator::where('role', $leaveRole->name)->exists();

        if ($usedInWorkflows || $usedInValidators) {
            return back()->with('error', 'Ce rôle est utilisé dans des workflows ou validateurs. Vous ne pouvez pas le supprimer.');
        }

        $leaveRole->delete();

        return redirect()->route('admin.leave-roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}