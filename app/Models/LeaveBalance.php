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

    // ✅ Accesseurs pour compatibilité avec le code existant
    public function getAvailableBalanceAttribute()
    {
        return $this->remaining ?? 0;
    }

    public function getAccruedBalanceAttribute()
    {
        return $this->total_entitled ?? 0;
    }

    public function getUsedBalanceAttribute()
    {
        return $this->total_taken ?? 0;
    }

    public function getPendingBalanceAttribute()
    {
        return $this->total_pending ?? 0;
    }

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function period()
    {
        return $this->belongsTo(LeavePeriod::class, 'period_id');
    }

    // Scope pour filtrer par employé
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Scope pour filtrer par type de congé
    public function scopeForLeaveType($query, $leaveTypeId)
    {
        return $query->where('leave_type_id', $leaveTypeId);
    }

    // Scope pour filtrer par période
    public function scopeForPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }
}