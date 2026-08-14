<?php

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
        'is_enabled',
        'local_name',
        'local_calculation_method',
        'local_weekend_days',
        'local_holiday_handling',
        'local_rounding_rule',
        'local_exclude_holidays',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'local_exclude_holidays' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class, 'leave_policy_id');
    }
}