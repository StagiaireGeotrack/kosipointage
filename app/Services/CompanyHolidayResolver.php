<?php
// app/Services/CompanyHolidayResolver.php

namespace App\Services;

use App\Models\CompanyHoliday;
use App\Models\SiteCompanyHolidaySetting;

class CompanyHolidayResolver
{
    public function resolve(CompanyHoliday $holiday, int $siteId): \stdClass
    {
        $override = SiteCompanyHolidaySetting::where('site_id', $siteId)
            ->where('company_holiday_id', $holiday->id)
            ->first();

        $r = new \stdClass();
        $r->id = $holiday->id;
        $r->site_id = $holiday->site_id;
        $r->is_global = true;
        $r->is_customizable = $holiday->is_customizable;
        $r->is_overridden = !is_null($override);
        $r->override_id = $override?->id;

        // Champs résolus (override ou global)
        $r->date = $override->date ?? $holiday->date;
        $r->name = $override->name ?? $holiday->name;
        $r->is_recurring = $override->is_recurring ?? $holiday->is_recurring;
        $r->is_active = $override->is_active ?? $holiday->is_active;

        return $r;
    }

    public function resolveCollection($holidays, int $siteId): \Illuminate\Support\Collection
    {
        return $holidays->map(function ($holiday) use ($siteId) {
            if ($holiday->isGlobal()) {
                return $this->resolve($holiday, $siteId);
            }

            $local = new \stdClass();
            foreach ($holiday->getAttributes() as $k => $v) {
                $local->{$k} = $v;
            }
            $local->is_global = false;
            $local->is_customizable = false;
            $local->is_overridden = false;
            $local->override_id = null;

            return $local;
        });
    }
}