<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $fillable = [
        'company_id',
        'created_by',
        'name',
        'code',
        'color',
        'unit',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Filtre automatique selon qui est connecté
     */
    protected static function booted(): void
    {
        static::addGlobalScope('visibility', function (Builder $builder) {
            $user = auth()->user();
            if (!$user) return;

            // Super admin (IsSuperAdmin = 1 ou SiegeID null) → voit TOUT
            if ($user instanceof \App\Models\Administration && ($user->IsSuperAdmin || is_null($user->SiegeID))) {
                return;
            }

            // Admin de siège → voit uniquement global + son siège
            $siegeId = $user->SiegeID ?? null;
            if ($siegeId) {
                $builder->where(function (Builder $q) use ($siegeId) {
                    $q->whereNull('company_id')
                      ->orWhere('company_id', $siegeId);
                });
            } else {
                $builder->whereNull('company_id');
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'company_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Administration::class, 'created_by');
    }

    public function policies(): HasMany
    {
        return $this->hasMany(LeavePolicy::class);
    }

    public function leavePolicies(): HasMany
    {
        return $this->hasMany(LeavePolicy::class);
    }

    public function ruleFields(): HasMany
    {
        return $this->hasMany(RuleField::class)->orderBy('sort_order');
    }

    public function calculationRules(): HasMany
    {
        return $this->hasMany(CalculationRule::class)->orderBy('sort_order');
    }
}