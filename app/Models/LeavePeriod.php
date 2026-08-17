<?php
// app/Models/LeavePeriod.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_periods';

    protected $fillable = [
        'site_id',
        'leave_type_id',
        'name',
        'start_date',
        'end_date',
        'submission_deadline',
        'allow_rollover',
        'max_rollover_days',
        'rollover_expiry_date',
        'is_default',
        'status',
        'is_active',
        'is_customizable',
    ];

    protected $casts = [
        'allow_rollover' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'is_customizable' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'submission_deadline' => 'date',
        'rollover_expiry_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['is_global'];

    /* Relations */
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeavePeriodSetting::class, 'leave_period_id');
    }

    /* Accessors */
    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

    /* Scopes */
    public function scopeVisibleForUser($query, $user)
    {
        if ($user && $user->IsSuperAdmin == 1) {
            return $query;
        }

        $siteId = $user ? $user->SiegeID : null;

        return $query->where(function ($q) use ($siteId) {
            $q->whereNull('site_id')
              ->orWhere('site_id', $siteId);
        });
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('site_id');
    }

    public function scopeForSite($query, int $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    public function scopeForLeaveType($query, int $leaveTypeId)
    {
        return $query->where('leave_type_id', $leaveTypeId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* Helpers */
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function settingForSite(int $siteId): ?SiteLeavePeriodSetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }
}