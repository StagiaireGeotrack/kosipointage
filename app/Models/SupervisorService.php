<?php
// app/Models/SupervisorService.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorService extends Model
{
    protected $table = 'supervisor_services';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'service_id',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Administration::class, 'admin_id', 'ID');
    }

    public function service()
    {
        return $this->belongsTo(Department::class, 'service_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Administration::class, 'created_by', 'ID');
    }
}
