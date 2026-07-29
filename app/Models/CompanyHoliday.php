<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    protected $table = 'company_holidays';

    protected $fillable = [
        'company_id',
        'name',
        'date',
        'is_half_day',
        'half_day_type',
        'is_recurring',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_half_day' => 'boolean',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];
}