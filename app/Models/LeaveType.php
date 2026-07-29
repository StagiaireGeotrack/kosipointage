<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'color',
        'unit',
        'deducts_balance',
        'requires_attachment',
        'attachment_threshold',
        'approval_required',
        'allow_negative_balance',
        'negative_limit',
        'visibility_level',
        'is_active',
    ];

    protected $casts = [
        'deducts_balance' => 'boolean',
        'approval_required' => 'boolean',
        'allow_negative_balance' => 'boolean',
        'is_active' => 'boolean',
        'negative_limit' => 'decimal:2',
    ];

    // Scope pour filtrer par siège (isolation multi-siège)
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}