<?php
// app/Models/LeaveType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_types';

    protected $fillable = [
        'site_id',
        'name',
        'code',
        'unit',
        'deducts_balance',
        'requires_attachment',
        'requires_attachment_after',
        'allow_negative_balance',
        'max_negative_limit',
        'color',
        'is_active',
        'is_customizable',
        'min_notice_days',
        'max_duration_per_request',
        'allow_overlap',
        'affects_team_availability',
    ];

    protected $casts = [
        'deducts_balance' => 'boolean',
        'allow_negative_balance' => 'boolean',
        'is_active' => 'boolean',
        'is_customizable' => 'boolean',
        'affects_team_availability' => 'boolean',
        'allow_overlap' => 'boolean',
        'min_notice_days' => 'integer',
        'max_duration_per_request' => 'decimal:2',
    ];

    protected $appends = ['is_global'];

    // Relations
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeaveTypeSetting::class, 'leave_type_id');
    }

    public function siteActivations()
    {
        return $this->hasMany(LeaveTypeSiteActivation::class, 'leave_type_id');
    }

    // Accessors
    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

    public function getUnitLabelAttribute(): string
    {
        return match($this->unit) {
            'days' => 'Jours',
            'half_days' => 'Demi-journées',
            'hours' => 'Heures',
            default => $this->unit,
        };
    }

    public function getRequiresAttachmentLabelAttribute(): string
    {
        return match($this->requires_attachment) {
            'never' => 'Jamais',
            'always' => 'Toujours',
            'after_duration' => 'Après durée',
            default => $this->requires_attachment,
        };
    }

    // Scopes
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

    // Helpers
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function settingForSite(int $siteId): ?SiteLeaveTypeSetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    public function isActiveForSite(int $siteId): bool
    {
        $activation = $this->siteActivations()->where('site_id', $siteId)->first();
        return $activation ? $activation->is_active : $this->is_active;
    }
}