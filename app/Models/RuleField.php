<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuleField extends Model
{
    protected $fillable = [
        'leave_type_id',
        'field_key',
        'field_type',
        'label',
        'default_value',
        'options',
        'validation',
        'conditions',
        'sort_order',
    ];

    protected $casts = [
        'options'    => 'array',
        'validation' => 'array',
        'conditions' => 'array',
    ];

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}