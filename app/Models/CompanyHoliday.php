<?php
// app/Models/CompanyHoliday.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyHoliday extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'company_holidays';

    protected $fillable = [
        'site_id',
        'date',
        'name',
        'is_recurring',
        'is_active',
        'is_customizable',  // <-- AJOUTÉ
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'is_customizable' => 'boolean',  // <-- AJOUTÉ
    ];

    protected $appends = ['is_global'];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteCompanyHolidaySetting::class, 'company_holiday_id');
    }

    public function getIsGlobalAttribute(): bool
    {
        return is_null($this->site_id);
    }

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

    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    public function settingForSite(int $siteId): ?SiteCompanyHolidaySetting
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }
}