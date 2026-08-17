<?php
// app/Http/Controllers/LeavePeriodController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepriseSiege;
use App\Models\LeavePeriod;
use App\Models\LeaveType;
use App\Models\SiteLeavePeriodSetting;
use App\Services\LeavePeriodResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeavePeriodController extends Controller
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

    public function index(Request $request)
    {
        $this->authorize('viewAny', LeavePeriod::class);

        $query = LeavePeriod::with(['site', 'leaveType'])
            ->visibleForUser(Auth::user())
            ->orderBy('name');

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $periods = $query->paginate(15);

        if (! $this->isSuperAdmin()) {
            $resolver = new LeavePeriodResolver();
            $resolvedPeriods = $resolver->resolveCollection($periods->getCollection(), $this->getUserSiteId());
            $periods->setCollection($resolvedPeriods);
        }

        $leaveTypes = LeaveType::where('is_active', true)
            ->visibleForUser(Auth::user())
            ->orderBy('name')
            ->get();

        // CHEMIN MODIFIÉ : conges.leave_periods.index
        return view('conges.leave_periods.index', compact('periods', 'leaveTypes'));
    }

    public function create()
    {
        $this->authorize('create', LeavePeriod::class);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        $leaveTypes = LeaveType::where('is_active', true)
            ->visibleForUser(Auth::user())
            ->orderBy('name')
            ->get();

        // CHEMIN MODIFIÉ : conges.leave_periods.create
        return view('conges.leave_periods.create', compact('sites', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeavePeriod::class);

        $isAdmin = $this->isSuperAdmin();
        $validated = $request->validate($this->rules($isAdmin));

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        $exists = LeavePeriod::where('name', $validated['name'])
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Une période avec ce nom existe déjà pour ce type de congé et ce siège.'])
                ->withInput();
        }

        $validated['allow_rollover'] = $request->boolean('allow_rollover');
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        LeavePeriod::create($validated);

        return redirect()->route('admin.leave-periods.index')
            ->with('success', 'Période de congé créée avec succès.');
    }

    public function show(LeavePeriod $leavePeriod)
    {
        $this->authorize('view', $leavePeriod);

        $resolved = $leavePeriod;
        if (! $this->isSuperAdmin() && $leavePeriod->isGlobal()) {
            $resolver = new LeavePeriodResolver();
            $resolved = $resolver->resolve($leavePeriod, $this->getUserSiteId());
        }

        // CHEMIN MODIFIÉ : conges.leave_periods.show
        return view('conges.leave_periods.show', compact('leavePeriod', 'resolved'));
    }

    public function edit(LeavePeriod $leavePeriod)
    {
        $this->authorize('update', $leavePeriod);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        $leaveTypes = LeaveType::where('is_active', true)
            ->visibleForUser(Auth::user())
            ->orderBy('name')
            ->get();

        $override = null;
        if (! $this->isSuperAdmin() && $leavePeriod->isGlobal() && $leavePeriod->is_customizable) {
            $override = SiteLeavePeriodSetting::where('site_id', $this->getUserSiteId())
                ->where('leave_period_id', $leavePeriod->id)
                ->first();
        }

        // CHEMIN MODIFIÉ : conges.leave_periods.edit
        return view('conges.leave_periods.edit', compact('leavePeriod', 'sites', 'leaveTypes', 'override'));
    }

    public function update(Request $request, LeavePeriod $leavePeriod)
    {
        $this->authorize('update', $leavePeriod);

        $isAdmin = $this->isSuperAdmin();

        if (! $isAdmin && $leavePeriod->isGlobal() && $leavePeriod->is_customizable) {
            return $this->updateOverride($request, $leavePeriod);
        }

        $validated = $request->validate($this->rules($isAdmin, $leavePeriod));

        if (! $isAdmin) {
            unset($validated['site_id']);
            $newSiteId = $leavePeriod->site_id;
        } else {
            $newSiteId = $validated['site_id'] ?? null;
        }

        if ($validated['name'] !== $leavePeriod->name || 
            $validated['leave_type_id'] != $leavePeriod->leave_type_id || 
            $newSiteId != $leavePeriod->site_id) {
            $exists = LeavePeriod::where('name', $validated['name'])
                ->where('leave_type_id', $validated['leave_type_id'])
                ->where('site_id', $newSiteId)
                ->where('id', '!=', $leavePeriod->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['name' => 'Une période avec ce nom existe déjà pour ce type de congé et ce siège.'])
                    ->withInput();
            }
        }

        $validated['allow_rollover'] = $request->boolean('allow_rollover');
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $leavePeriod->update($validated);

        return redirect()->route('admin.leave-periods.index')
            ->with('success', 'Période de congé mise à jour avec succès.');
    }

    public function destroy(LeavePeriod $leavePeriod)
    {
        $this->authorize('delete', $leavePeriod);

        $leavePeriod->delete();

        return redirect()->route('admin.leave-periods.index')
            ->with('success', 'Période de congé supprimée.');
    }

    private function updateOverride(Request $request, LeavePeriod $leavePeriod)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'submission_deadline' => 'nullable|date',
            'allow_rollover' => 'nullable|boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:preparing,open,closed',
            'is_active' => 'nullable|boolean',
        ]);

        $overrideData = [
            'site_id' => $siteId,
            'leave_period_id' => $leavePeriod->id,
        ];

        if ($request->has('name')) {
            $overrideData['name'] = $validated['name'] ?: null;
        }
        if ($request->has('start_date')) {
            $overrideData['start_date'] = $validated['start_date'];
        }
        if ($request->has('end_date')) {
            $overrideData['end_date'] = $validated['end_date'];
        }
        if ($request->has('submission_deadline')) {
            $overrideData['submission_deadline'] = $validated['submission_deadline'];
        }
        if ($request->has('allow_rollover')) {
            $overrideData['allow_rollover'] = $request->boolean('allow_rollover');
        }
        if ($request->has('max_rollover_days')) {
            $overrideData['max_rollover_days'] = $validated['max_rollover_days'];
        }
        if ($request->has('rollover_expiry_date')) {
            $overrideData['rollover_expiry_date'] = $validated['rollover_expiry_date'];
        }
        if ($request->has('is_default')) {
            $overrideData['is_default'] = $request->boolean('is_default');
        }
        if ($request->has('status')) {
            $overrideData['status'] = $validated['status'];
        }
        if ($request->has('is_active')) {
            $overrideData['is_active'] = $request->boolean('is_active');
        }

        SiteLeavePeriodSetting::updateOrCreate(
            [
                'site_id' => $siteId,
                'leave_period_id' => $leavePeriod->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.leave-periods.index')
            ->with('success', 'Configuration locale de la période mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?LeavePeriod $ignore = null): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'leave_type_id' => [
                'required',
                'exists:leave_types,id',
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'submission_deadline' => 'nullable|date',
            'allow_rollover' => 'nullable|boolean',
            'max_rollover_days' => 'nullable|integer|min:0',
            'rollover_expiry_date' => 'nullable|date',
            'status' => 'required|in:preparing,open,closed',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}