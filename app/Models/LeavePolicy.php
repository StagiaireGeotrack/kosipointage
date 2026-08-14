<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    use HasFactory;

    protected $table = 'leave_policies';

    protected $fillable = [
        'site_id',
        'name',
        'calculation_method',
        'reference_schedule_id',
        'holiday_handling',
        'rounding_rule',
        'weekend_days',
        'exclude_holidays',
        'is_default',
        'is_customizable',
    ];

    protected $casts = [
        'exclude_holidays' => 'boolean',
        'is_default' => 'boolean',
        'is_customizable' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeavePolicySetting::class, 'leave_policy_id');
    }

    public function settingForSite(int $siteId): ?SiteLeavePolicySetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function scopeForTenant($query, ?int $siteId = null)
    {
        if ($siteId) {
            return $query->where(function ($q) use ($siteId) {
                $q->whereNull('site_id')->orWhere('site_id', $siteId);
            });
        }
        return $query;
    }
}