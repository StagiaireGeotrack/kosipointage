<?php
// app/Http/Controllers/LeavePolicyController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepriseSiege;
use App\Models\LeavePolicy;
use App\Models\SiteLeavePolicySetting;
use App\Services\LeavePolicyResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeavePolicyController extends Controller
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
        $this->authorize('viewAny', LeavePolicy::class);

        $query = LeavePolicy::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $policies = $query->paginate(15);

        // Admin site : on résout les overrides
        if (! $this->isSuperAdmin()) {
            $resolver = new LeavePolicyResolver();
            $resolvedPolicies = $resolver->resolveCollection($policies->getCollection(), $this->getUserSiteId());
            $policies->setCollection($resolvedPolicies);
        }

        return view('conges.leave_policies.index', compact('policies'));
    }

    public function create()
    {
        $this->authorize('create', LeavePolicy::class);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        return view('conges.leave_policies.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeavePolicy::class);

        $isAdmin = $this->isSuperAdmin();
        $validated = $request->validate($this->rules($isAdmin));

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        $exists = LeavePolicy::where('name', $validated['name'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Une politique avec ce nom existe déjà pour ce siège.'])
                ->withInput();
        }

        $validated['exclude_holidays'] = $request->boolean('exclude_holidays', true);
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        LeavePolicy::create($validated);

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Politique de congé créée avec succès.');
    }

    public function show(LeavePolicy $leavePolicy)
    {
        $this->authorize('view', $leavePolicy);

        $resolved = $leavePolicy;
        if (! $this->isSuperAdmin() && $leavePolicy->isGlobal()) {
            $resolver = new LeavePolicyResolver();
            $resolved = $resolver->resolve($leavePolicy, $this->getUserSiteId());
        }

        return view('conges.leave_policies.show', compact('leavePolicy', 'resolved'));
    }

    public function edit(LeavePolicy $leavePolicy)
    {
        $this->authorize('update', $leavePolicy);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        // Charge l'override existant si on est en mode "édition locale d'un global"
        $override = null;
        if (! $this->isSuperAdmin() && $leavePolicy->isGlobal() && $leavePolicy->is_customizable) {
            $override = SiteLeavePolicySetting::where('site_id', $this->getUserSiteId())
                ->where('leave_policy_id', $leavePolicy->id)
                ->first();
        }

        return view('conges.leave_policies.edit', compact('leavePolicy', 'sites', 'override'));
    }

    public function update(Request $request, LeavePolicy $leavePolicy)
    {
        $this->authorize('update', $leavePolicy);

        $isAdmin = $this->isSuperAdmin();

        // CAS SPÉCIAL : Admin site + global customizable → on écrit dans l'override
        if (! $isAdmin && $leavePolicy->isGlobal() && $leavePolicy->is_customizable) {
            return $this->updateOverride($request, $leavePolicy);
        }

        // CAS STANDARD : Super Admin ou politique locale
        $validated = $request->validate($this->rules($isAdmin, $leavePolicy));

        if (! $isAdmin) {
            unset($validated['site_id']);
            $newSiteId = $leavePolicy->site_id;
        } else {
            $newSiteId = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        if ($validated['name'] !== $leavePolicy->name || $newSiteId != $leavePolicy->site_id) {
            $exists = LeavePolicy::where('name', $validated['name'])
                ->where('site_id', $newSiteId)
                ->where('id', '!=', $leavePolicy->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['name' => 'Une politique avec ce nom existe déjà pour ce siège.'])
                    ->withInput();
            }
        }

        $validated['exclude_holidays'] = $request->boolean('exclude_holidays', true);
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $leavePolicy->update($validated);

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Politique de congé mise à jour avec succès.');
    }

    public function destroy(LeavePolicy $leavePolicy)
    {
        $this->authorize('delete', $leavePolicy);

        $leavePolicy->delete();

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Politique de congé supprimée.');
    }

    public function restore($id)
    {
        $policy = LeavePolicy::withTrashed()->findOrFail($id);
        $this->authorize('update', $policy);

        $policy->restore();

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Politique de congé restaurée avec succès.');
    }

    private function updateOverride(Request $request, LeavePolicy $leavePolicy)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'calculation_method' => 'nullable|in:working_days,business_days,hours',
            'reference_schedule_id' => 'nullable|integer',
            'holiday_handling' => 'nullable|in:skip,count,split',
            'rounding_rule' => 'nullable|in:none,half_day,full_day,quarter_hour,half_hour',
            'weekend_days' => 'nullable|in:saturday_sunday,friday_saturday,sunday_only,none',
            'exclude_holidays' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $overrideData = [
            'site_id' => $siteId,
            'leave_policy_id' => $leavePolicy->id,
        ];

        if ($request->has('name')) {
            $overrideData['name'] = $validated['name'] ?: null;
        }
        if ($request->has('calculation_method')) {
            $overrideData['calculation_method'] = $validated['calculation_method'];
        }
        if ($request->has('reference_schedule_id')) {
            $overrideData['reference_schedule_id'] = $validated['reference_schedule_id'];
        }
        if ($request->has('holiday_handling')) {
            $overrideData['holiday_handling'] = $validated['holiday_handling'];
        }
        if ($request->has('rounding_rule')) {
            $overrideData['rounding_rule'] = $validated['rounding_rule'];
        }
        if ($request->has('weekend_days')) {
            $overrideData['weekend_days'] = $validated['weekend_days'];
        }
        if ($request->has('exclude_holidays')) {
            $overrideData['exclude_holidays'] = $request->boolean('exclude_holidays');
        }
        if ($request->has('is_default')) {
            $overrideData['is_default'] = $request->boolean('is_default');
        }
        if ($request->has('is_active')) {
            $overrideData['is_active'] = $request->boolean('is_active');
        }

        SiteLeavePolicySetting::updateOrCreate(
            [
                'site_id' => $siteId,
                'leave_policy_id' => $leavePolicy->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Configuration locale de la politique mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?LeavePolicy $ignore = null): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'calculation_method' => 'required|in:working_days,business_days,hours',
            'reference_schedule_id' => 'nullable|integer',
            'holiday_handling' => 'required|in:skip,count,split',
            'rounding_rule' => 'required|in:none,half_day,full_day,quarter_hour,half_hour',
            'weekend_days' => 'required|in:saturday_sunday,friday_saturday,sunday_only,none',
            'exclude_holidays' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}