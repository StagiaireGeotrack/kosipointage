<?php
// app/Models/Entreprise.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class Entreprise extends Model
{
    use Loggable;

    protected $table = 'Entreprises';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Nom',
        'Logo',
        'Nom_Lieu_Ville',
        'Latitude',
        'Longitude',
        'RadiusInMeters',
        'CreatedAt',
        'Actived',
        'SiegeID',
    ];
    
    protected $casts = [
        'Latitude' => 'decimal:8',
        'Longitude' => 'decimal:8',
        'RadiusInMeters' => 'decimal:8',
        'CreatedAt' => 'datetime',
        'Actived' => 'boolean',
    ];
    
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