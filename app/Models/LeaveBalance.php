<?php
// app/Models/LeaveBalance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $table = 'leave_balances';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'period_id',
        'total_entitled',
        'total_taken',
        'total_pending',
        'remaining',
        'carryover_from_previous',
    ];

    protected $casts = [
        'total_entitled' => 'decimal:2',
        'total_taken' => 'decimal:2',
        'total_pending' => 'decimal:2',
        'remaining' => 'decimal:2',
        'carryover_from_previous' => 'decimal:2',
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

    public function transactions()
    {
        return $this->hasMany(LeaveBalanceTransaction::class);
    }

    // Accessors
    public function getAvailableAttribute()
    {
        return $this->remaining - $this->total_pending;
    }

    public function getTotalAttribute()
    {
        return $this->total_entitled + $this->carryover_from_previous;
    }

    // Scopes
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForType($query, $typeId)
    {
        return $query->where('leave_type_id', $typeId);
    }

    public function scopeForPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }
}