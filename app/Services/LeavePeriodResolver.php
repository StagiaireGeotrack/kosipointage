<?php

namespace App\Services;

use App\Models\LeavePeriod;
use App\Models\SiteLeavePeriod;

class LeavePeriodResolver
{
    /**
     * Résout une période pour un siège donné (fallback global → local)
     */
    public static function resolve(int $leavePeriodId, int $siteId): ?\stdClass
    {
        $global = LeavePeriod::find($leavePeriodId);

        if (!$global) {
            return null;
        }

        $local = SiteLeavePeriod::where('site_id', $siteId)
            ->where('leave_period_id', $leavePeriodId)
            ->where('is_active', true)
            ->first();

        return (object) [
            'id' => $global->id,
            'site_id' => $global->site_id,
            'leave_type_id' => $global->leave_type_id,
            'name' => $local?->name ?? $global->name,
            'start_date' => $local?->start_date ?? $global->start_date,
            'end_date' => $local?->end_date ?? $global->end_date,
            'submission_deadline' => $local?->submission_deadline ?? $global->submission_deadline,
            'allow_rollover' => $local?->allow_rollover ?? $global->allow_rollover,
            'max_rollover_days' => $local?->max_rollover_days ?? $global->max_rollover_days,
            'rollover_expiry_date' => $local?->rollover_expiry_date ?? $global->rollover_expiry_date,
            'is_default' => $local?->is_default ?? $global->is_default,
            'status' => $local?->status ?? $global->status,
            'is_active' => $local?->is_active ?? $global->is_active,
            'has_override' => !is_null($local),
        ];
    }

    /**
     * Récupère toutes les périodes actives résolues pour un siège
     */
    public static function forSite(int $siteId, ?int $leaveTypeId = null): array
    {
        $query = LeavePeriod::forSite($siteId)->active();

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        return $query->get()->map(function ($period) use ($siteId) {
            return self::resolve($period->id, $siteId);
        })->all();
    }
}