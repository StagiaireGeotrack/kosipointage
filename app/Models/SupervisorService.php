<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorService extends Model
{
    protected $table = 'supervisor_services';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'service_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relation vers le compte responsable
    public function admin()
    {
        return $this->belongsTo(Administration::class, 'admin_id', 'ID');
    }

    // Relation vers le service
    public function service()
    {
        return $this->belongsTo(Department::class, 'service_id', 'id');
    }
}