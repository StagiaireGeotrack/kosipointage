<?php
// app/Models/SiteLeaveWorkflowSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLeaveWorkflowSetting extends Model
{
    use HasFactory;

    protected $table = 'site_leave_workflow_settings';

    protected $fillable = [
        'site_id',
        'leave_workflow_id',
        'name',
        'description',
        'steps',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leaveWorkflow()
    {
        return $this->belongsTo(LeaveWorkflow::class, 'leave_workflow_id');
    }
}