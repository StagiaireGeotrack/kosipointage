<?php
// app/Models/LeaveWorkflow.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveWorkflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_workflows';

    protected $fillable = [
        'site_id',
        'name',
        'description',
        'steps',
        'is_default',
        'is_customizable',
        'is_active',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_default' => 'boolean',
        'is_customizable' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['is_global'];

    // Relations
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeaveWorkflowSetting::class, 'leave_workflow_id');
    }

    // Accessors
    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

    public function getStepsCountAttribute(): int
    {
        return count($this->steps ?? []);
    }

    public function getStepRolesAttribute(): string
    {
        if (empty($this->steps)) return 'Aucune étape';
        
        $roles = array_map(function($step) {
            return $step['label'] ?? $step['role'] ?? 'Étape ' . ($step['order'] ?? '');
        }, $this->steps);
        
        return implode(' → ', $roles);
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

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helpers
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function settingForSite(int $siteId): ?SiteLeaveWorkflowSetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    public function getFirstStepRole(): ?string
    {
        if (empty($this->steps)) return null;
        return $this->steps[0]['role'] ?? null;
    }

    public function getLastStepRole(): ?string
    {
        if (empty($this->steps)) return null;
        $last = end($this->steps);
        return $last['role'] ?? null;
    }
}