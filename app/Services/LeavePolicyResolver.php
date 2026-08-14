<?php

namespace App\Services;

use App\Models\LeavePolicy;
use App\Models\SiteLeavePolicySetting;

class LeavePolicyResolver
{
    public function resolve(LeavePolicy $policy, int $siteId): \stdClass
    {
        $override = SiteLeavePolicySetting::where('site_id', $siteId)
            ->where('leave_policy_id', $policy->id)
            ->first();

        $r = new \stdClass();

        // ─── Identifiants & flags ───
        $r->id              = $policy->id;
        $r->site_id         = $policy->site_id;
        $r->is_global       = true;
        $r->is_customizable = (bool) $policy->is_customizable;
        $r->is_default      = (bool) $policy->is_default;
        $r->is_overridden   = ! is_null($override);
        $r->override_id     = $override?->id;
        $r->override_enabled = $override?->is_enabled ?? true;

        // ─── Valeurs effectives (override > global) ───
        $r->name               = $override->local_name               ?? $policy->name;
        $r->calculation_method = $override->local_calculation_method ?? $policy->calculation_method;
        $r->weekend_days       = $override->local_weekend_days       ?? $policy->weekend_days;
        $r->holiday_handling   = $override->local_holiday_handling   ?? $policy->holiday_handling;
        $r->rounding_rule      = $override->local_rounding_rule      ?? $policy->rounding_rule;
        $r->exclude_holidays   = $override->local_exclude_holidays   ?? $policy->exclude_holidays;

        return $r;
    }

    public function resolveCollection($policies, int $siteId): \Illuminate\Support\Collection
    {
        return $policies->map(function ($policy) use ($siteId) {
            if ($policy->isGlobal()) {
                return $this->resolve($policy, $siteId);
            }

            // Policy locale : on expose la même interface pour l'index
            $local = new \stdClass();
            $local->id              = $policy->id;
            $local->site_id         = $policy->site_id;
            $local->is_global       = false;
            $local->is_customizable = false;
            $local->is_default      = (bool) $policy->is_default;
            $local->is_overridden   = false;
            $local->override_id     = null;
            $local->override_enabled = true;

            $local->name               = $policy->name;
            $local->calculation_method = $policy->calculation_method;
            $local->weekend_days       = $policy->weekend_days;
            $local->holiday_handling   = $policy->holiday_handling;
            $local->rounding_rule      = $policy->rounding_rule;
            $local->exclude_holidays   = $policy->exclude_holidays;

            return $local;
        });
    }
}