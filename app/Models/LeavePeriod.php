<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePeriod extends Model
{
    protected $table = 'leave_periods';

    protected $fillable = [
        'company_id',
        'name',
        'start_date',
        'end_date',
        'is_closed',
        'closed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
    ];
}