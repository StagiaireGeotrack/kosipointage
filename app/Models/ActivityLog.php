<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_email',
        'user_role',
        'action',
        'model_type',
        'model_id',
        'model_label',
        'description',
        'ip_address',
        'user_agent',
        'SiegeID',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
