<?php
// app/Models/SiteLeavePeriodSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLeavePeriodSetting extends Model
{
    use HasFactory;

    protected $table = 'site_leave_periods';

    protected $fillable = [
        'site_id',
        'leave_period_id',
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
    ];

    protected $casts = [
        'allow_rollover' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'submission_deadline' => 'date',
        'rollover_expiry_date' => 'date',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leavePeriod()
    {
        return $this->belongsTo(LeavePeriod::class, 'leave_period_id');
    }
}