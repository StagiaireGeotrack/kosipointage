<?php
// app/Models/CongeValidation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CongeValidation extends Model
{
    protected $table = 'conge_validations';

    protected $fillable = [
        'SiegeID',
        'matricule',
        'nom_prenom',
        'email',
        'telephone',
        'date_heure_debut',
        'date_heure_fin',
        'status',
        'raison',
        'raison_rejection',
        'date_creation',
        'date_validation',
    ];

    protected $casts = [
        'date_heure_debut' => 'datetime',
        'date_heure_fin'   => 'datetime',
        'date_creation'    => 'datetime',
        'date_validation'  => 'datetime',
    ];

    // ──────────────────────────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────────────────────────

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    // ──────────────────────────────────────────────────────────────────
    // Scopes utiles
    // ──────────────────────────────────────────────────────────────────

    /** Demandes en attente de validation */
    public function scopeEnCours($query)
    {
        return $query->where('status', 'en_cours');
    }

    /** Demandes validées */
    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    /** Demandes refusées */
    public function scopeNotValidated($query)
    {
        return $query->where('status', 'not_validated');
    }

    // ──────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────

    /** Libellé lisible du statut */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'validated'     => 'Validé',
            'not_validated' => 'Refusé',
            default         => 'En cours',
        };
    }

    /** Couleur Bootstrap associée au statut */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'validated'     => 'success',
            'not_validated' => 'danger',
            default         => 'warning',
        };
    }
}
