<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    protected $table = 'leave_policies';

    protected $fillable = [
        'company_id',
        'leave_type_id',
        'rules',
        'is_active',
    ];

    protected $casts = [
        'rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
    public function values()
{
    return $this->hasMany(PolicyValue::class, 'leave_policy_id');
}
}