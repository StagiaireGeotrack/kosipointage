<?php
// app/Models/LeaveType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'deducts_balance'        => 'boolean',
        'allow_negative_balance' => 'boolean',
        'is_active'              => 'boolean',
        'is_customizable'        => 'boolean',
    ];
protected $appends = ['is_global'];
    
    /* Relations */
   



    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    /* Accessors */
    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteLeaveTypeSetting::class, 'leave_type_id');
    }

    public function settingForSite(int $siteId): ?SiteLeaveTypeSetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    /* Scopes */
    public function scopeVisibleForUser($query, $admin)
    {
        if ($admin && $admin->IsSuperAdmin) {
            return $query;
        }

        $siteId = $admin ? $admin->SiegeID : null;

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

    /* Helpers */
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }
}