<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicy;
use App\Models\SiteLeavePolicySetting;
use App\Models\EntrepriseSiege;
use App\Services\LeavePolicyResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeavePolicyController extends Controller
{
    private function isSuperAdmin(): bool
    {
        return Auth::user()->IsSuperAdmin == 1;
    }

    private function getUserSiteId(): ?int
    {
        return Auth::user()->SiegeID;
    }

    public function index()
    {
        $isAdmin = $this->isSuperAdmin();
        $siteId = $isAdmin ? null : $this->getUserSiteId();

        $query = LeavePolicy::with('site')->forTenant($siteId)->orderBy('name');

        $policies = $query->get();

        if (! $isAdmin) {
            $resolver = new LeavePolicyResolver();
            $policies = $resolver->resolveCollection($policies, $this->getUserSiteId());
        }

        return view('conges.leave_policies.index', compact('policies'));
    }

    public function create()
    {
        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        return view('conges.leave_policies.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $isAdmin = $this->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:100',
            'calculation_method' => 'required|in:working_days,business_days,hours',
            'holiday_handling' => 'required|in:skip,count,split',
            'rounding_rule' => 'required|in:none,half_day,full_day,quarter_hour,half_hour',
            'weekend_days' => 'required|in:saturday_sunday,friday_saturday,sunday_only',
            'exclude_holidays' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        $validated['exclude_holidays'] = $request->boolean('exclude_holidays', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        LeavePolicy::create($validated);

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Règle de calcul créée avec succès.');
    }

    public function edit(LeavePolicy $leavePolicy)
    {
        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

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
        $isAdmin = $this->isSuperAdmin();

        // CAS SPÉCIAL : Admin site + global customizable → override
        if (! $isAdmin && $leavePolicy->isGlobal() && $leavePolicy->is_customizable) {
            return $this->updateOverride($request, $leavePolicy);
        }

        $rules = [
            'name' => 'required|string|max:100',
            'calculation_method' => 'required|in:working_days,business_days,hours',
            'holiday_handling' => 'required|in:skip,count,split',
            'rounding_rule' => 'required|in:none,half_day,full_day,quarter_hour,half_hour',
            'weekend_days' => 'required|in:saturday_sunday,friday_saturday,sunday_only',
            'exclude_holidays' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
            unset($validated['site_id']);
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        $validated['exclude_holidays'] = $request->boolean('exclude_holidays', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $leavePolicy->update($validated);

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Règle de calcul mise à jour avec succès.');
    }

    public function destroy(LeavePolicy $leavePolicy)
    {
        $leavePolicy->delete();

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Règle de calcul supprimée.');
    }

    private function updateOverride(Request $request, LeavePolicy $leavePolicy)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'calculation_method' => 'nullable|in:working_days,business_days,hours',
            'holiday_handling' => 'nullable|in:skip,count,split',
            'rounding_rule' => 'nullable|in:none,half_day,full_day,quarter_hour,half_hour',
            'weekend_days' => 'nullable|in:saturday_sunday,friday_saturday,sunday_only',
            'exclude_holidays' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $overrideData = [
            'site_id' => $siteId,
            'leave_policy_id' => $leavePolicy->id,
        ];

        if ($request->has('name')) {
            $overrideData['local_name'] = $validated['name'] ?: null;
        }
        if ($request->has('calculation_method')) {
            $overrideData['local_calculation_method'] = $validated['calculation_method'] ?: null;
        }
        if ($request->has('weekend_days')) {
            $overrideData['local_weekend_days'] = $validated['weekend_days'] ?: null;
        }
        if ($request->has('holiday_handling')) {
            $overrideData['local_holiday_handling'] = $validated['holiday_handling'] ?: null;
        }
        if ($request->has('rounding_rule')) {
            $overrideData['local_rounding_rule'] = $validated['rounding_rule'] ?: null;
        }
        if ($request->has('exclude_holidays')) {
            $overrideData['local_exclude_holidays'] = $request->boolean('exclude_holidays');
        }
        if ($request->has('is_active')) {
            $overrideData['is_enabled'] = $request->boolean('is_active');
        }

        SiteLeavePolicySetting::updateOrCreate(
            ['site_id' => $siteId, 'leave_policy_id' => $leavePolicy->id],
            $overrideData
        );

        return redirect()->route('admin.leave-policies.index')
            ->with('success', 'Configuration locale de la règle mise à jour.');
    }
}