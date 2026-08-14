<?php

namespace App\Http\Controllers;

use App\Models\LeavePeriod;
use App\Models\SiteLeavePeriod;
use App\Models\LeaveType;
use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeavePeriodController extends Controller
{
    private function isSuperAdmin(): bool
    {
        return is_null(Auth::user()->employe);
    }

    private function getUserSiteId(): ?int
    {
        return Auth::user()->employe?->SiegeID ?? Auth::user()->site_id ?? null;
    }

    // ============================================================
    // INDEX
    // ============================================================
    public function index()
    {
        $userSiteId = $this->getUserSiteId();
        $isSuperAdmin = $this->isSuperAdmin();

        if ($isSuperAdmin) {
            $periods = LeavePeriod::with('leaveType', 'site')
                ->withCount('siteSettings')
                ->orderByRaw('site_id IS NULL DESC, site_id, start_date DESC')
                ->paginate(20);
        } else {
            // Admin siège : globaux + siens, avec eager-load de SON override
            $periods = LeavePeriod::with(['leaveType', 'site', 'siteSettings' => function ($q) use ($userSiteId) {
                    $q->where('site_id', $userSiteId);
                }])
                ->where(function ($q) use ($userSiteId) {
                    $q->whereNull('site_id')
                      ->orWhere('site_id', $userSiteId);
                })
                ->orderBy('start_date', 'desc')
                ->paginate(20);

            // On "résout" chaque période en mémoire pour la vue
            foreach ($periods as $period) {
                $ov = $period->siteSettings->first();

                $period->resolved_name              = $ov?->name ?? $period->name;
                $period->resolved_start_date        = $ov?->start_date ?? $period->start_date;
                $period->resolved_end_date          = $ov?->end_date ?? $period->end_date;
                $period->resolved_submission_deadline = $ov?->submission_deadline ?? $period->submission_deadline;
                $period->resolved_allow_rollover    = $ov?->allow_rollover ?? $period->allow_rollover;
                $period->resolved_max_rollover_days = $ov?->max_rollover_days ?? $period->max_rollover_days;
                $period->resolved_rollover_expiry_date = $ov?->rollover_expiry_date ?? $period->rollover_expiry_date;
                $period->resolved_is_default        = $ov?->is_default ?? $period->is_default;
                $period->resolved_status            = $ov?->status ?? $period->status;
                $period->resolved_is_active         = $ov?->is_active ?? $period->is_active;
                $period->has_override               = !is_null($ov);
            }
        }

        return view('conges.leave_periods.index', compact('periods', 'isSuperAdmin', 'userSiteId'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $sites = $isSuperAdmin ? EntrepriseSiege::orderBy('nom')->get() : collect();

        if ($isSuperAdmin) {
            $leaveTypes = LeaveType::where('is_active', true)
                ->orderByRaw('site_id IS NULL DESC, site_id, name')
                ->get();
        } else {
            $leaveTypes = LeaveType::where(function ($q) use ($userSiteId) {
                    $q->whereNull('site_id')
                      ->orWhere('site_id', $userSiteId);
                })
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('conges.leave_periods.create', compact('leaveTypes', 'sites', 'isSuperAdmin', 'userSiteId'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $isSuperAdmin = $this->isSuperAdmin();

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'submission_deadline' => 'nullable|date|before_or_equal:end_date',
            'allow_rollover' => 'boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date|after:end_date',
            'is_default' => 'boolean',
            'status' => 'required|in:preparing,open,closed',
        ]);

        if ($isSuperAdmin) {
            $request->validate(['scope' => 'required|in:global,site']);
            $siteId = $request->scope === 'global' ? null : (int) $request->site_id;
            if ($request->scope === 'site' && !$siteId) {
                return back()->with('error', 'Veuillez choisir un siège.');
            }
        } else {
            $siteId = $this->getUserSiteId();
            if (!$siteId) {
                return back()->with('error', 'Impossible de déterminer votre siège.');
            }
        }

        $validated['site_id'] = $siteId;
        $validated['allow_rollover'] = $request->boolean('allow_rollover');
        $validated['is_default'] = $request->boolean('is_default');

        if ($validated['is_default']) {
            $q = LeavePeriod::where('leave_type_id', $validated['leave_type_id']);
            if ($siteId) {
                $q->where('site_id', $siteId);
            } else {
                $q->whereNull('site_id');
            }
            $q->update(['is_default' => false]);
        }

        LeavePeriod::create($validated);

        $msg = $siteId
            ? 'Période créée pour le siège sélectionné.'
            : 'Période globale créée avec succès.';

        return redirect()->route('admin.leave-periods.index')->with('success', $msg);
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $period = LeavePeriod::with('siteSettings.site')->findOrFail($id);

        // Sécurité
        if (!$isSuperAdmin) {
            if (!is_null($period->site_id) && $period->site_id !== $userSiteId) {
                abort(403, 'Cette période appartient à un autre siège.');
            }
        }

        // Types
        $leaveTypes = $isSuperAdmin
            ? LeaveType::where('is_active', true)->orderByRaw('site_id IS NULL DESC, site_id, name')->get()
            : LeaveType::where(function ($q) use ($userSiteId) {
                  $q->whereNull('site_id')->orWhere('site_id', $userSiteId);
              })->where('is_active', true)->orderBy('name')->get();

        // Admin siège qui édite un GLOBAL → il va créer son override
        $siteOverride = null;
        $isEditingGlobalAsSite = false;

        if (!$isSuperAdmin && is_null($period->site_id)) {
            $isEditingGlobalAsSite = true;
            $siteOverride = SiteLeavePeriod::where('leave_period_id', $period->id)
                ->where('site_id', $userSiteId)
                ->first();
        }

        // Bloc "Personnalisations par siège" visible uniquement pour Super Admin
        $siblingSites = collect();
        if ($isSuperAdmin) {
            $siblingSites = EntrepriseSiege::where('ID', '!=', $period->site_id ?? 0)->get();
        }

        return view('conges.leave_periods.edit', compact(
            'period', 'leaveTypes', 'siblingSites',
            'isSuperAdmin', 'userSiteId', 'siteOverride', 'isEditingGlobalAsSite'
        ));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $period = LeavePeriod::findOrFail($id);

        if (!$isSuperAdmin && !is_null($period->site_id) && $period->site_id !== $userSiteId) {
            abort(403);
        }

        // ADMIN SIÈGE + GLOBAL = création d'un override, pas de modification du global
        if (!$isSuperAdmin && is_null($period->site_id)) {
            return $this->updateOrCreateOverride($request, $period, $userSiteId);
        }

        // SUPER ADMIN ou ADMIN SIÈGE sur une période spécifique → update normal
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'submission_deadline' => 'nullable|date|before_or_equal:end_date',
            'allow_rollover' => 'boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date|after:end_date',
            'is_default' => 'boolean',
            'status' => 'required|in:preparing,open,closed',
            'is_active' => 'boolean',
        ]);

        $validated['allow_rollover'] = $request->boolean('allow_rollover');
        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active'] = $request->boolean('is_active', true);

        $period->update($validated);

        return redirect()->route('admin.leave-periods.index')->with('success', 'Période mise à jour.');
    }

    // ============================================================
    // DESTROY
    // ============================================================
    public function destroy($id)
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $period = LeavePeriod::findOrFail($id);

        if (!$isSuperAdmin && !is_null($period->site_id) && $period->site_id !== $userSiteId) {
            abort(403);
        }

        $period->delete();

        return redirect()->route('admin.leave-periods.index')->with('success', 'Période supprimée.');
    }

    // ============================================================
    // OVERRIDE (Admin Siège qui modifie un global)
    // ============================================================
    private function updateOrCreateOverride(Request $request, LeavePeriod $period, int $siteId)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'submission_deadline' => 'nullable|date',
            'allow_rollover' => 'nullable|boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:preparing,open,closed',
        ]);

        foreach (['allow_rollover', 'is_default'] as $field) {
            $validated[$field] = $request->has($field) ? $request->boolean($field) : null;
        }

        $validated['site_id'] = $siteId;
        $validated['leave_period_id'] = $period->id;
        $validated['is_active'] = true;

        SiteLeavePeriod::updateOrCreate(
            ['site_id' => $siteId, 'leave_period_id' => $period->id],
            $validated
        );

        return redirect()->route('admin.leave-periods.index')
            ->with('success', 'Votre personnalisation a été enregistrée. Le global reste inchangé.');
    }

    // ============================================================
    // OVERRIDE (Super Admin uniquement — gardé pour compatibilité)
    // ============================================================
    public function storeSiteOverride(Request $request, $leavePeriodId)
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $period = LeavePeriod::findOrFail($leavePeriodId);

        if (!is_null($period->site_id)) {
            return back()->with('error', 'Cette période est déjà spécifique à un siège.');
        }

        $validated = $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'name' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'submission_deadline' => 'nullable|date',
            'allow_rollover' => 'nullable|boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:preparing,open,closed',
        ]);

        if (!$isSuperAdmin && (int)$validated['site_id'] !== $userSiteId) {
            abort(403);
        }

        foreach (['allow_rollover', 'is_default'] as $field) {
            $validated[$field] = $request->has($field) ? $request->boolean($field) : null;
        }

        $validated['leave_period_id'] = $period->id;

        SiteLeavePeriod::updateOrCreate(
            ['site_id' => $validated['site_id'], 'leave_period_id' => $period->id],
            $validated
        );

        return back()->with('success', 'Personnalisation enregistrée pour ce siège.');
    }

    public function destroySiteOverride($siteLeavePeriodId)
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $userSiteId = $this->getUserSiteId();

        $override = SiteLeavePeriod::with('leavePeriod')->findOrFail($siteLeavePeriodId);

        if (!$isSuperAdmin && $override->site_id !== $userSiteId) {
            abort(403);
        }

        $override->delete();
        return back()->with('success', 'Personnalisation supprimée. Le siège reprend la règle globale.');
    }
}