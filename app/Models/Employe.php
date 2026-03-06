<?php
// app/Models/Employe.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class Employe extends Model
{
    use Loggable;
    public string $logLabelField = 'Nom';

    protected $table = 'Employes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Nom',
        'BadgeID',
        'num_mat',
        'HasBiometricSetup',
        'HasFaceSetup',
        'FaceEncodingPath',
        'Pin',
        'CreatedAt',
        'deleted',
        'Actived',
        'SiegeID',
    ];
    
    protected $casts = [
        'HasBiometricSetup' => 'boolean',
        'HasFaceSetup' => 'boolean',
        'CreatedAt' => 'datetime',
        'Actived' => 'boolean',
    ];
    
    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }
    
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class, 'employee_id', 'ID');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SiegeScope());
    }
}