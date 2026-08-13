<?php

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
    ];

    protected $casts = [
        'deducts_balance'        => 'boolean',
        'allow_negative_balance' => 'boolean',
        'is_active'              => 'boolean',
    ];

    /* Relations */
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    /* Scopes */
    public function scopeVisibleForUser($query, $admin)
    {
        // Superadmin voit tout (globaux + tous sièges)
        if ($admin && $admin->IsSuperAdmin) {
            return $query;
        }

        $siteId = $admin ? $admin->SiegeID : null;

        return $query->where(function ($q) use ($siteId) {
            $q->whereNull('site_id')              // globaux
              ->orWhere('site_id', $siteId);     // ou ceux de mon siège
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