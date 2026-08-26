<?php
// app/Models/LeavePolicy.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicy extends Model
{
    use HasFactory, SoftDeletes;

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
        'is_active',
    ];

    protected $casts = [
        'exclude_holidays' => 'boolean',
        'is_default' => 'boolean',
        'is_customizable' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['is_global'];

    // ============================================
    // RELATIONS
    // ============================================

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeavePolicySetting::class, 'leave_policy_id');
    }

    public function referenceSchedule()
    {
        return $this->belongsTo(ReferenceSchedule::class, 'reference_schedule_id');
    }

    /**
     * ✅ RELATION AVEC LES ASSIGNMENTS (CORRIGÉE)
     * Utilise 'leave_policy_id' comme clé étrangère
     */
    public function assignments()
    {
        return $this->hasMany(LeavePolicyAssignment::class, 'leave_policy_id');
    }

    /**
     * ✅ RELATION AVEC LE TYPE DE CONGÉ (AJOUTÉE)
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

    // ============================================
    // SCOPES
    // ============================================

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * ✅ SCOPE POUR UN TYPE DE CONGÉ SPÉCIFIQUE (AJOUTÉ)
     */
    public function scopeForLeaveType($query, $leaveTypeId)
    {
        return $query->where('leave_type_id', $leaveTypeId);
    }

    // ============================================
    // HELPERS
    // ============================================

    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function settingForSite(int $siteId): ?SiteLeavePolicySetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    public function getCalculationMethodLabel(): string
    {
        return match($this->calculation_method) {
            'working_days' => 'Jours ouvrés',
            'business_days' => 'Jours ouvrables',
            'hours' => 'Heures',
            default => $this->calculation_method,
        };
    }

    public function getHolidayHandlingLabel(): string
    {
        return match($this->holiday_handling) {
            'skip' => 'Ignorer les jours fériés',
            'count' => 'Compter les jours fériés',
            'split' => 'Séparer (demi-journées)',
            default => $this->holiday_handling,
        };
    }

    public function getRoundingRuleLabel(): string
    {
        return match($this->rounding_rule) {
            'none' => 'Aucun arrondi',
            'half_day' => 'Demi-journée',
            'full_day' => 'Journée entière',
            'quarter_hour' => 'Quart d\'heure',
            'half_hour' => 'Demi-heure',
            default => $this->rounding_rule,
        };
    }

    public function getWeekendDaysLabel(): string
    {
        return match($this->weekend_days) {
            'saturday_sunday' => 'Samedi et Dimanche',
            'friday_saturday' => 'Vendredi et Samedi',
            'sunday_only' => 'Dimanche uniquement',
            'none' => 'Aucun (7 jours sur 7)',
            default => $this->weekend_days,
        };
    }
}