<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToSite;

class LeaveWorkflow extends Model
{
    use BelongsToSite;

    protected $fillable = [
        'site_id', 'leave_type_id', 'step_order',
        'approver_type', 'specific_user_id',
        'min_days_trigger', 'max_days_trigger', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(EntreprisesSiege::class, 'site_id', 'ID');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function specificUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Employe::class, 'specific_user_id', 'ID');
    }
}