<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLeavePeriod extends Model
{
    use HasFactory;

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
        'start_date' => 'date',
        'end_date' => 'date',
        'submission_deadline' => 'date',
        'rollover_expiry_date' => 'date',
        'allow_rollover' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function leavePeriod()
    {
        return $this->belongsTo(LeavePeriod::class);
    }

    public function site()
    {
        return $this->belongsTo(\App\Models\EntrepriseSiege::class, 'site_id', 'ID');
    }
}