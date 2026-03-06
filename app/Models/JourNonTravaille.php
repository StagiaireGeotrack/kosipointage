<?php
// app/Models/JourNonTravaille.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class JourNonTravaille extends Model
{
    use Loggable;

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
        'created_at' => 'datetime'
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

}