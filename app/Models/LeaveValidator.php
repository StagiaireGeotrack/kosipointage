<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveValidator extends Model
{
    protected $fillable = ['employee_id', 'site_id', 'role', 'is_active'];

    public function employee()
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
    }
}