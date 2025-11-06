<?php
// app/Models/JourNonTravaille.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JourNonTravaille extends Model
{
    protected $table = 'jours_non_travailles';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'Date',
        'Nom',
        'Type',
        'SiegeID',
        'Recurrent',
        'Description',
        'Actived',
    ];
    
    protected $casts = [
        'Date' => 'date',
        'Recurrent' => 'boolean',
        'Actived' => 'boolean',
    ];
    
    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }
    
    /**
     * Scope pour les jours actifs uniquement
     */
    public function scopeActif($query)
    {
        return $query->where('Actived', true);
    }
    
    /**
     * Scope pour une période donnée
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('Date', [$dateDebut, $dateFin]);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SiegeScope());
    }
}