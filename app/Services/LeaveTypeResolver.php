<?php
// app/Services/LeaveTypeResolver.php

namespace App\Services;

use App\Models\LeaveType;
use App\Models\SiteLeaveTypeSetting;

class LeaveTypeResolver
{
    public function resolve(LeaveType $type, int $siteId): \stdClass
    {
        $override = SiteLeaveTypeSetting::where('site_id', $siteId)
            ->where('leave_type_id', $type->id)
            ->first();

        $r = new \stdClass();
        $r->id = $type->id;
        $r->code = $type->code;
        $r->unit = $type->unit;
        $r->site_id = $type->site_id;
        $r->is_global = true;
        $r->site_name = '—';
        $r->is_customizable = $type->is_customizable;
        $r->is_overridden = !is_null($override);
        $r->override_id = $override?->id;
        $r->deleted_at = $type->deleted_at;

        // Champs résolus (override ou global)
        $r->name = $override->local_name ?? $type->name;
        $r->color = $override->local_color ?? $type->color;
        $r->requires_attachment = $override->local_requires_attachment ?? $type->requires_attachment;
        $r->requires_attachment_after = $override->local_requires_attachment_after ?? $type->requires_attachment_after;
        $r->allow_negative_balance = $override->local_allow_negative_balance ?? $type->allow_negative_balance;
        $r->max_negative_limit = $override->local_max_negative_limit ?? $type->max_negative_limit;
        $r->deducts_balance = $override->local_deducts_balance ?? $type->deducts_balance;
        $r->is_active = $override->is_enabled ?? $type->is_active;

        // Nouveaux champs résolus
        $r->min_notice_days = $override->local_min_notice_days ?? $type->min_notice_days ?? 0;
        $r->max_duration_per_request = $override->local_max_duration_per_request ?? $type->max_duration_per_request;
        $r->allow_overlap = $override->local_allow_overlap ?? $type->allow_overlap ?? false;
        $r->affects_team_availability = $type->affects_team_availability ?? true;

        return $r;
    }

    public function resolveCollection($types, int $siteId): \Illuminate\Support\Collection
    {
        return $types->map(function ($type) use ($siteId) {
            if ($type->isGlobal()) {
                return $this->resolve($type, $siteId);
            }

            $local = new \stdClass();
            foreach ($type->getAttributes() as $k => $v) {
                $local->{$k} = $v;
            }
            $local->is_global = false;
            $local->site_name = $type->site->Nom ?? '—';
            $local->is_customizable = false;
            $local->is_overridden = false;
            $local->override_id = null;

            // Valeurs par défaut
            $local->min_notice_days = $type->min_notice_days ?? 0;
            $local->max_duration_per_request = $type->max_duration_per_request;
            $local->allow_overlap = $type->allow_overlap ?? false;
            $local->affects_team_availability = $type->affects_team_availability ?? true;

            return $local;
        });
    }
}