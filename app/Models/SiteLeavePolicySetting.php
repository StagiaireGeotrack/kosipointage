<?php
// app/Models/SiteLeavePolicySetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLeavePolicySetting extends Model
{
    use HasFactory;

    protected $table = 'site_leave_policy_settings';

    protected $fillable = [
        'site_id',
        'leave_policy_id',
        'name',
        'calculation_method',
        'reference_schedule_id',
        'holiday_handling',
        'rounding_rule',
        'weekend_days',
        'exclude_holidays',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'exclude_holidays' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class, 'leave_policy_id');
    }

    public function referenceSchedule()
    {
        return $this->belongsTo(ReferenceSchedule::class, 'reference_schedule_id');
    }
}