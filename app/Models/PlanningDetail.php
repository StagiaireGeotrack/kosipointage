<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanningDetail extends Model
{
    protected $table = 'planning_details';

    protected $fillable = [
        'planning_id',
        'employe_id',
        'date',
        'heure_debut',
        'heure_fin',
        'pause_debut',
        'pause_fin',
        'deuxieme_debut',
        'deuxieme_fin',
        'commentaire',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }

    public function comparaison(): HasOne
    {
        return $this->hasOne(ComparaisonPlanning::class);
    }

    public function getLibelleStatutAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'confirme' => 'Confirmé',
            'effectue' => 'Effectué',
            'annule' => 'Annulé',
            'absent' => 'Absent',
            default => $this->statut,
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'planifie' => '#3B82F6',
            'confirme' => '#22C55E',
            'effectue' => '#8B5CF6',
            'annule' => '#EF4444',
            'absent' => '#F59E0B',
            default => '#6B7280',
        };
    }

    public function getInitialesEmployeAttribute(): string
    {
        if (!$this->employe) return '?';
        $nom = $this->employe->Nom ?? '';
        $prenom = $this->employe->Prenom ?? '';
        return strtoupper(substr($nom, 0, 1) . substr($prenom, 0, 1));
    }

    public function scopeByEmploye($query, $employeId)
    {
        return $query->where('employe_id', $employeId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeEntreDates($query, $debut, $fin)
    {
        return $query->whereBetween('date', [$debut, $fin]);
    }
}
