<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_requests';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'period_id',
        'start_date',
        'end_date',
        'duration',
        'status',
        'reason',
        'submitted_at',
        'comment',
        'attachment_path',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'submitted_at' => 'datetime',

        'duration' => 'decimal:2',
    ];

    // Relations avec les bons noms
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(LeavePeriod::class, 'period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'approved_by');
    }

    // Si vous voulez garder les anciens noms comme alias
    public function type()
    {
        return $this->leaveType();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
public function attachments()
{
    return $this->hasMany(LeaveRequestAttachment::class);
}
    public function scopeInDateRange($query, $start, $end)
    {
        return $query->whereBetween('start_date', [$start, $end])
                     ->orWhereBetween('end_date', [$start, $end]);
    }
}