<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMeta extends Model
{
    protected $table = 'employee_meta';
    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'department_name',
        'job_title',
        'hire_date',
        'employment_status',
        'company_id',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];
}