<?php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class Conge extends Model
{
    use Loggable;
    public string $logLabelField = 'employee_id';

    protected $table = 'conges';
    public $timestamps = false;
    
    protected $fillable = [
        'employee_id',
        'SiegeID',
        'date_debut',
        'date_fin',
        'type_conge',
        'commentaire',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    protected static function boot()
    {
        parent::boot();
        
        // Appliquer le scope global
        static::addGlobalScope(new SiegeScope());
        
        // Auto-remplir le SiegeID lors de la création
        static::creating(function ($conge) {
            if (!$conge->SiegeID) {
                // Récupérer le SiegeID de l'employé
                $employe = \App\Models\Employe::withoutGlobalScope(SiegeScope::class)
                    ->find($conge->employee_id);
                
                if ($employe) {
                    $conge->SiegeID = $employe->SiegeID;
                }
            }
        });
    }
}