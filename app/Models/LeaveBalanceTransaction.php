<?php
// app/Models/LeaveBalanceTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalanceTransaction extends Model
{
    use HasFactory;

    protected $table = 'leave_balance_transactions';

    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'period_id',
        'amount',
        'type',
        'source',
        'reference_id',
        'reference_type',
        'description',
        'metadata',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function period()
    {
        return $this->belongsTo(LeavePeriod::class);
    }

    public function creator()
    {
        return $this->belongsTo(Administration::class, 'created_by', 'ID');
    }

    // Accessors
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'credit' => 'Crédit',
            'debit' => 'Débit',
            'carryover' => 'Report',
            'adjustment' => 'Ajustement',
            'opening' => 'Solde initial',
            default => $this->type,
        };
    }

    public function getAmountLabelAttribute()
    {
        return ($this->amount > 0 ? '+' : '') . $this->amount;
    }

    // Scopes
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    public function scopeCredits($query)
    {
        return $query->where('amount', '>', 0);
    }

    public function scopeDebits($query)
    {
        return $query->where('amount', '<', 0);
    }
}