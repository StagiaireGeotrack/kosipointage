<?php
// app/Services/LeaveWorkflowResolver.php

namespace App\Services;

use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;

class LeaveWorkflowResolver
{
    public function resolve(LeaveWorkflow $workflow, int $siteId): \stdClass
    {
        $override = SiteLeaveWorkflowSetting::where('site_id', $siteId)
            ->where('leave_workflow_id', $workflow->id)
            ->first();

        $r = new \stdClass();
        $r->id = $workflow->id;
        $r->site_id = $workflow->site_id;
        $r->is_global = true;
        $r->site_name = '—';
        $r->is_customizable = $workflow->is_customizable;
        $r->is_overridden = !is_null($override);
        $r->override_id = $override?->id;
        $r->deleted_at = $workflow->deleted_at;

        // Champs résolus (override ou global)
        $r->name = $override->name ?? $workflow->name;
        $r->description = $override->description ?? $workflow->description;
        $r->steps = $override->steps ?? $workflow->steps;
        $r->is_default = $override->is_default ?? $workflow->is_default;
        $r->is_active = $override->is_active ?? $workflow->is_active;

        return $r;
    }

    public function resolveCollection($workflows, int $siteId): \Illuminate\Support\Collection
    {
        return $workflows->map(function ($workflow) use ($siteId) {
            if ($workflow->isGlobal()) {
                return $this->resolve($workflow, $siteId);
            }

            $local = new \stdClass();
            foreach ($workflow->getAttributes() as $k => $v) {
                $local->{$k} = $v;
            }
            $local->is_global = false;
            $local->site_name = $workflow->site->Nom ?? '—';
            $local->is_customizable = false;
            $local->is_overridden = false;
            $local->override_id = null;

            return $local;
        });
    }
}