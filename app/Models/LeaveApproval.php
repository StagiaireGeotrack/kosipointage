<?php
// app/Models/LeaveApproval.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveApproval extends Model
{
    use HasFactory;

    protected $table = 'leave_approvals';

    protected $fillable = [
        'leave_request_id',
        'workflow_step_id',
        'approver_id',
        'step_order',
        'status',
        'is_current',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    // ✅ AJOUTER LES CASTS POUR CONVERTIR LES DATES
    protected $casts = [
        'is_current' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
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