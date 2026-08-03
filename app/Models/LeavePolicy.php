<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeavePolicy extends Model
{
    protected $table = 'leave_policies';

    protected $fillable = [
        'company_id',
        'leave_type_id',
        'rules',
        'is_active',
    ];

    protected $casts = [
        'rules' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Le type de congé global lié
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    /**
     * Les valeurs des champs dynamiques (policy_values)
     */
    public function values(): HasMany
    {
        return $this->hasMany(PolicyValue::class, 'leave_policy_id');
    }
}