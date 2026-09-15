<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EvenementPlanning extends Model
{
    use SoftDeletes;

    protected $table = 'evenements_planning';

    protected $fillable = [
        'siege_id',
        'service_id',
        'poste_id',
        'titre',
        'description',
        'adresse',
        'type',
        'debut',
        'fin',
        'toute_la_journee',
        'couleur',
        'cree_par',
    ];

    protected $casts = [
        'debut' => 'datetime',
        'fin' => 'datetime',
        'toute_la_journee' => 'boolean',
    ];

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'siege_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'service_id');
    }

    public function poste(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'poste_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function employes(): BelongsToMany
    {
        return $this->belongsToMany(Employe::class, 'evenement_employes', 'evenement_id', 'employe_id');
    }

    public function getLibelleTypeAttribute(): string
    {
        return match($this->type) {
            'formation' => 'Formation',
            'deplacement' => 'Déplacement professionnel',
            'reunion' => 'Réunion',
            'conges_exceptionnel' => 'Congé exceptionnel',
            'autre' => 'Autre',
            default => $this->type,
        };
    }

    public function getIconeTypeAttribute(): string
    {
        return match($this->type) {
            'formation' => 'fa-graduation-cap',
            'deplacement' => 'fa-plane',
            'reunion' => 'fa-users',
            'conges_exceptionnel' => 'fa-calendar-times',
            'autre' => 'fa-plus-circle',
            default => 'fa-calendar',
        };
    }

    public function getCouleurEvenementAttribute(): string
    {
        return $this->couleur ?? match($this->type) {
            'formation' => '#8B5CF6',
            'deplacement' => '#3B82F6',
            'reunion' => '#F59E0B',
            'conges_exceptionnel' => '#EF4444',
            'autre' => '#6B7280',
            default => '#6B7280',
        };
    }
}
