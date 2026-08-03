<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyValue extends Model
{
    protected $fillable = [
        'leave_policy_id',
        'rule_field_id',
        'value',
    ];

    public function ruleField(): BelongsTo
    {
        return $this->belongsTo(RuleField::class);
    }

    public function leavePolicy(): BelongsTo
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}