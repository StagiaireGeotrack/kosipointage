<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'unit',
        'deducts_balance',
        'allow_negative_balance',
        'negative_limit',
        'requires_attachment',
        'attachment_threshold',
        'approval_required',
        'color',
        'visibility_level',
        'is_active',
    ];

    protected $casts = [
        'deducts_balance' => 'boolean',
        'allow_negative_balance' => 'boolean',
        'approval_required' => 'boolean',
        'is_active' => 'boolean',
    ];
}