<?php
// app/Services/LeavePolicyResolver.php

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
        $r->id = $policy->id;
        $r->site_id = $policy->site_id;
        $r->is_global = true;
        $r->site_name = '—';
        $r->is_customizable = $policy->is_customizable;
        $r->is_overridden = !is_null($override);
        $r->override_id = $override?->id;
        $r->deleted_at = $policy->deleted_at;

        // Champs résolus (override ou global)
        $r->name = $override->name ?? $policy->name;
        $r->calculation_method = $override->calculation_method ?? $policy->calculation_method;
        $r->reference_schedule_id = $override->reference_schedule_id ?? $policy->reference_schedule_id;
        $r->holiday_handling = $override->holiday_handling ?? $policy->holiday_handling;
        $r->rounding_rule = $override->rounding_rule ?? $policy->rounding_rule;
        $r->weekend_days = $override->weekend_days ?? $policy->weekend_days;
        $r->exclude_holidays = $override->exclude_holidays ?? $policy->exclude_holidays;
        $r->is_default = $override->is_default ?? $policy->is_default;
        $r->is_active = $override->is_active ?? $policy->is_active;

        return $r;
    }

    public function resolveCollection($policies, int $siteId): \Illuminate\Support\Collection
    {
        return $policies->map(function ($policy) use ($siteId) {
            if ($policy->isGlobal()) {
                return $this->resolve($policy, $siteId);
            }

            $local = new \stdClass();
            foreach ($policy->getAttributes() as $k => $v) {
                $local->{$k} = $v;
            }
            $local->is_global = false;
            $local->site_name = $policy->site->Nom ?? '—';
            $local->is_customizable = false;
            $local->is_overridden = false;
            $local->override_id = null;

            return $local;
        });
    }
}