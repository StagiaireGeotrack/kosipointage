<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    protected $table = 'leave_policies';

    protected $fillable = [
        'company_id',
        'leave_type_id',
        'name',
        'accrual_method',
        'accrual_rate',
        'accrual_frequency',
        'day_type',
        'exclude_holidays',
        'exclude_non_working_days',
        'notice_period_days',
        'allow_half_days',
        'rounding_rule',
        'allow_overdraft',
        'max_overdraft_days',
        'carry_over_allowed',
        'max_carry_over_days',
        'is_active',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}