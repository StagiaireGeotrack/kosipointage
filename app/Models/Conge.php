<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conge extends Model
{
    protected $table = 'conges';
    
    protected $fillable = [
        'employee_id',
        'date_debut',
        'date_fin',
        'type_conge',
        'commentaire',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }
}