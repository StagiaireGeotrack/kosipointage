<?php
// app/Models/Pointage.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pointage extends Model
{
    protected $table = 'Pointages';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'employee_id',
        'type_',
        'auth_method',
        'timestamp_',
        'latitude',
        'longitude',
        'company_id',
        'photo_path',
        'synced',
        'SiegeID',
    ];
    
    protected $casts = [
        'timestamp_' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'synced' => 'boolean',
    ];
    
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'company_id', 'ID');
    }

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SiegeScope());
    }
}