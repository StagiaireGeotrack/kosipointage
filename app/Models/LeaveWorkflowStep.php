<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveWorkflowStep extends Model
{
    protected $fillable = ['workflow_id', 'step_order', 'name', 'approver_role', 'is_active'];

    public function workflow()
    {
        return $this->belongsTo(LeaveWorkflow::class);
    }
}