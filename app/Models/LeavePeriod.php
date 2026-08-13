<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToSite;

class LeavePeriod extends Model
{
    use BelongsToSite;

    protected $fillable = [
        'site_id', 'name', 'start_date', 'end_date',
        'allow_rollover', 'max_rollover_days', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'allow_rollover' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(EntreprisesSiege::class, 'site_id', 'ID');
    }
}