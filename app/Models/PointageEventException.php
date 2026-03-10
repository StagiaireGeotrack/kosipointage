<?php
// app/Models/PointageEventException.php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointageEventException extends Model
{
    use Loggable;
    public string $logLabelField = 'employee_id';
    
    public $timestamps = false;
    protected $table = 'pointage_event_exceptions';

    protected $fillable = [
        'employee_id',
        'date',
        'error_type',
        'SiegeID',
        'acknowledged_by',
        'acknowledged_at',
        'note',
    ];

    protected $casts = [
        'date'            => 'date',
        'acknowledged_at' => 'datetime',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(Administration::class, 'acknowledged_by', 'ID');
    }
}
