<?php

namespace App\Http\Controllers;

use App\Models\LeaveValidator;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\LeaveRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveValidatorController extends Controller
{
    private function isSuperAdmin(): bool
    {
        return Auth::user() && Auth::user()->IsSuperAdmin == 1;
    }

    private function getUserSiteId(): ?int
    {
        return Auth::user() ? Auth::user()->SiegeID : null;
    }

    // ============================================
    // INDEX - Liste des validateurs
    // ============================================
    public function index(Request $request)
    {
        $query = LeaveValidator::with(['employee', 'site']);

        if (!$this->isSuperAdmin()) {
            $siteId = $this->getUserSiteId();
            if ($siteId) {
                $query->where('site_id', $siteId);
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($request->filled('site_id') && $this->isSuperAdmin()) {
            $query->where('site_id', $request->site_id);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $validators = $query->orderBy('site_id')->orderBy('role')->paginate(15);
        $sites = $this->isSuperAdmin() ? EntrepriseSiege::all() : collect();

        return view('leave_validators.index', compact('validators', 'sites'));
    }

    // ============================================
    // CREATE - Formulaire de création
    // ============================================
    public function create()
    {
        $siteId = $this->getUserSiteId();
        $sites = $this->isSuperAdmin() ? EntrepriseSiege::all() : EntrepriseSiege::where('ID', $siteId)->get();
        
        $employees = collect();
        if ($siteId) {
            $employees = Employe::where('SiegeID', $siteId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();
        }

        // 🔥 Rôles visibles pour ce site (globaux + site)
        $roles = LeaveRole::where('is_active', true)
            ->where(function ($q) use ($siteId) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $siteId);
            })
            ->orderBy('name')
            ->get();

        return view('leave_validators.create', compact('sites', 'employees', 'siteId', 'roles'));
    }

    // ============================================
    // EDIT - Formulaire d'édition
    // ============================================
    public function edit(LeaveValidator $leaveValidator)
    {
        if (!$this->isSuperAdmin() && $leaveValidator->site_id != $this->getUserSiteId()) {
            abort(403);
        }

        $sites = $this->isSuperAdmin() ? EntrepriseSiege::all() : EntrepriseSiege::where('ID', $leaveValidator->site_id)->get();
        $employees = Employe::where('SiegeID', $leaveValidator->site_id)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        // 🔥 Rôles visibles pour ce site (globaux + site)
        $siteId = $leaveValidator->site_id;
        $roles = LeaveRole::where('is_active', true)
            ->where(function ($q) use ($siteId) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $siteId);
            })
            ->orderBy('name')
            ->get();

        return view('leave_validators.edit', compact('leaveValidator', 'sites', 'employees', 'roles'));
    }

    // ============================================
    // STORE - Créer un validateur
    // ============================================
    public function store(Request $request)
    {
        // Règles de validation
        $rules = [
            'employee_id' => 'required|exists:employes,ID',
            'role' => 'required|string|exists:leave_roles,name', // 🔥 Vérifier que le rôle existe dans leave_roles
            'is_active' => 'nullable|boolean',
            'site_id' => 'required|exists:entreprises_sieges,ID',
        ];

        // Pour les non-superadmins, on force le site_id de l'utilisateur connecté
        if (!$this->isSuperAdmin()) {
            $request->merge(['site_id' => $this->getUserSiteId()]);
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Vérifier que le rôle existe bien pour ce site (global ou site)
        $roleExists = LeaveRole::where('name', $validated['role'])
            ->where(function ($q) use ($validated) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $validated['site_id']);
            })
            ->exists();

        if (!$roleExists) {
            return back()->withErrors(['role' => 'Ce rôle n\'est pas disponible pour ce site.'])->withInput();
        }

        // Vérification des doublons
        $exists = LeaveValidator::where('employee_id', $validated['employee_id'])
            ->where('site_id', $validated['site_id'])
            ->where('role', $validated['role'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['employee_id' => 'Cet employé a déjà ce rôle sur ce site.'])->withInput();
        }

        LeaveValidator::create($validated);
        return redirect()->route('admin.leave-validators.index')
            ->with('success', 'Validateur ajouté avec succès.');
    }

    // ============================================
    // UPDATE - Mettre à jour un validateur
    // ============================================
    public function update(Request $request, LeaveValidator $leaveValidator)
    {
        // Vérification des droits (même site)
        if (!$this->isSuperAdmin() && $leaveValidator->site_id != $this->getUserSiteId()) {
            abort(403);
        }

        $rules = [
            'employee_id' => 'required|exists:employes,ID',
            'role' => 'required|string|exists:leave_roles,name', // 🔥 Vérifier que le rôle existe dans leave_roles
            'is_active' => 'nullable|boolean',
            'site_id' => 'required|exists:entreprises_sieges,ID',
        ];

        if (!$this->isSuperAdmin()) {
            $request->merge(['site_id' => $leaveValidator->site_id]); // on garde le site existant
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Vérifier que le rôle existe bien pour ce site (global ou site)
        $roleExists = LeaveRole::where('name', $validated['role'])
            ->where(function ($q) use ($validated) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $validated['site_id']);
            })
            ->exists();

        if (!$roleExists) {
            return back()->withErrors(['role' => 'Ce rôle n\'est pas disponible pour ce site.'])->withInput();
        }

        // Vérification des doublons (sauf pour cet enregistrement)
        $exists = LeaveValidator::where('employee_id', $validated['employee_id'])
            ->where('site_id', $validated['site_id'])
            ->where('role', $validated['role'])
            ->where('id', '!=', $leaveValidator->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['employee_id' => 'Cet employé a déjà ce rôle sur ce site.'])->withInput();
        }

        $leaveValidator->update($validated);
        return redirect()->route('admin.leave-validators.index')
            ->with('success', 'Validateur mis à jour.');
    }

    // ============================================
    // DESTROY - Supprimer un validateur
    // ============================================
    public function destroy(LeaveValidator $leaveValidator)
    {
        if (!$this->isSuperAdmin() && $leaveValidator->site_id != $this->getUserSiteId()) {
            abort(403);
        }
        $leaveValidator->delete();
        return redirect()->route('admin.leave-validators.index')
            ->with('success', 'Validateur supprimé.');
    }

    // ============================================
    // API - Récupérer les employés par site
    // ============================================
    public function getEmployeesBySite(Request $request)
    {
        $siteId = $request->input('site_id');
        if (!$siteId) return response()->json([]);
        $employees = Employe::where('SiegeID', $siteId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get(['ID', 'Nom', 'num_mat']);
        return response()->json($employees);
    }
}