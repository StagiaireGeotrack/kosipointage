<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait BelongsToSite
{
    protected static function bootBelongsToSite(): void
    {
        // Scope global : filtre auto par site_id
        static::addGlobalScope('site', function ($query) {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            $siteId = $user->SiegeID ?? null;

            // Super admin voit tout
            if ($user->IsSuperAdmin ?? false) {
                return;
            }

            $table = (new static)->getTable();

            if (Schema::hasColumn($table, 'site_id')) {
                $query->where(function ($q) use ($siteId) {
                    $q->where('site_id', $siteId)
                      ->orWhereNull('site_id');
                });
            }
        });

        // Auto-inject site_id à la création
        static::creating(function ($model) {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            $siteId = $user->SiegeID ?? null;

            if (($user->IsSuperAdmin ?? false) === false && Schema::hasColumn($model->getTable(), 'site_id')) {
                if (is_null($model->site_id)) {
                    $model->site_id = $siteId;
                }
            }
        });
    }
}