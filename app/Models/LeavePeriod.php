<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',      // NULL = global, X = spécifique à ce siège
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

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeavePeriod::class, 'leave_period_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Périodes globales
    public function scopeGlobal($query)
    {
        return $query->whereNull('site_id');
    }

    // Périodes spécifiques à un siège
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}