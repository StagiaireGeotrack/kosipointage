<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $fillable = [
        'leave_request_id',
        'workflow_step_id',
        'approver_id',
        'step_order',
        'status',
        'is_current',
        'approved_at',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employe::class, 'approver_id');
    }

    public function step()
    {
        return $this->belongsTo(LeaveWorkflowStep::class, 'workflow_step_id');
    }
}