<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToSite;

class CompanyHoliday extends Model
{
    use BelongsToSite;

    protected $fillable = [
        'site_id', 'date', 'name', 'is_recurring',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(EntreprisesSiege::class, 'site_id', 'ID');
    }
}