<?php
// app/Services/LeavePeriodResolver.php

namespace App\Services;

use App\Models\LeavePeriod;
use App\Models\SiteLeavePeriodSetting;

class LeavePeriodResolver
{
    public function resolve(LeavePeriod $period, int $siteId): \stdClass
    {
        $override = SiteLeavePeriodSetting::where('site_id', $siteId)
            ->where('leave_period_id', $period->id)
            ->first();

        $r = new \stdClass();
        $r->id = $period->id;
        $r->leave_type_id = $period->leave_type_id;
        $r->site_id = $period->site_id;
        $r->is_global = true;
        $r->site_name = '—';
        $r->is_customizable = $period->is_customizable;
        $r->is_overridden = !is_null($override);
        $r->override_id = $override?->id;
        $r->deleted_at = $period->deleted_at;

        // Champs résolus (override ou global)
        $r->name = $override->name ?? $period->name;
        $r->start_date = $override->start_date ?? $period->start_date;
        $r->end_date = $override->end_date ?? $period->end_date;
        $r->submission_deadline = $override->submission_deadline ?? $period->submission_deadline;
        $r->allow_rollover = $override->allow_rollover ?? $period->allow_rollover;
        $r->max_rollover_days = $override->max_rollover_days ?? $period->max_rollover_days;
        $r->rollover_expiry_date = $override->rollover_expiry_date ?? $period->rollover_expiry_date;
        $r->is_default = $override->is_default ?? $period->is_default;
        $r->status = $override->status ?? $period->status;
        $r->is_active = $override->is_active ?? $period->is_active;

        // Ajouter les relations
        if ($period->relationLoaded('leaveType')) {
            $r->leaveType = $period->leaveType;
        }
        if ($period->relationLoaded('site')) {
            $r->site = $period->site;
        }

        return $r;
    }

    public function resolveCollection($periods, int $siteId): \Illuminate\Support\Collection
    {
        return $periods->map(function ($period) use ($siteId) {
            if ($period->isGlobal()) {
                return $this->resolve($period, $siteId);
            }

            $local = new \stdClass();
            foreach ($period->getAttributes() as $k => $v) {
                $local->{$k} = $v;
            }
            $local->is_global = false;
            $local->site_name = $period->site->Nom ?? '—';
            $local->is_customizable = false;
            $local->is_overridden = false;
            $local->override_id = null;

            // Ajouter les relations
            if ($period->relationLoaded('leaveType')) {
                $local->leaveType = $period->leaveType;
            }
            if ($period->relationLoaded('site')) {
                $local->site = $period->site;
            }

            return $local;
        });
    }
}