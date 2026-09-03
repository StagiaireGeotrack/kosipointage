<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComparaisonPlanning extends Model
{
    protected $table = 'comparaisons_planning';

    protected $fillable = [
        'planning_detail_id',
        'pointage_id',
        'heure_debut_prevue',
        'heure_fin_prevue',
        'heure_debut_reelle',
        'heure_fin_reelle',
        'ecart_debut_minutes',
        'ecart_fin_minutes',
        'ecart_total_minutes',
        'statut',
        'date_comparaison',
    ];

    protected $casts = [
        'date_comparaison' => 'date',
    ];

    public function planningDetail(): BelongsTo
    {
        return $this->belongsTo(PlanningDetail::class);
    }

    public function pointage(): BelongsTo
    {
        return $this->belongsTo(Pointage::class);
    }

    public function getLibelleStatutAttribute(): string
    {
        return match($this->statut) {
            'ponctuel' => 'À l\'heure ✅',
            'retard' => 'Retard ⚠️',
            'avance' => 'En avance ⚡',
            'absent' => 'Absent ❌',
            'pause_manquante' => 'Pause manquante ⚠️',
            'inconnu' => 'Non comparé ❓',
            default => $this->statut,
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'ponctuel' => '#22C55E',
            'retard' => '#EF4444',
            'avance' => '#F59E0B',
            'absent' => '#EF4444',
            'pause_manquante' => '#F59E0B',
            'inconnu' => '#6B7280',
            default => '#6B7280',
        };
    }

    public function getLibelleEcartAttribute(): string
    {
        if ($this->ecart_total_minutes === null) return '-';
        if ($this->ecart_total_minutes > 0) {
            return '+' . $this->ecart_total_minutes . ' min';
        }
        return $this->ecart_total_minutes . ' min';
    }
}
