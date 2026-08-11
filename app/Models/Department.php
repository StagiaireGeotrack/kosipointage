<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $fillable = ['company_id', 'site_id', 'name', 'code', 'manager_employee_id'];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
    }

    public function managerEmployee()
    {
        return $this->belongsTo(Employe::class, 'manager_employee_id');
    }

    public function employes()
    {
        return $this->hasMany(Employe::class, 'department_id');
    }
}