<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraireType extends Model
{
    use SoftDeletes;

    protected $table = 'horaires_types';

    protected $fillable = [
        'poste_id',
        'site_id',
        'jours_travailles',
        'heure_debut',
        'heure_fin',
        'pause_debut',
        'pause_fin',
        'deuxieme_debut',
        'deuxieme_fin',
        'par_defaut',
        'cree_par',
    ];

    protected $casts = [
        'par_defaut' => 'boolean',
    ];

    public function poste(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'poste_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Administration::class, 'cree_par');
    }

    public function getLibelleHoraireAttribute(): string
    {
        $horaire = $this->heure_debut . ' - ' . $this->heure_fin;
        if ($this->deuxieme_debut && $this->deuxieme_fin) {
            $horaire .= ' / ' . $this->deuxieme_debut . ' - ' . $this->deuxieme_fin;
        }
        return $horaire;
    }

    public function getLibellePauseAttribute(): string
    {
        if ($this->pause_debut && $this->pause_fin) {
            return $this->pause_debut . ' - ' . $this->pause_fin;
        }
        return 'Aucune pause';
    }

    public function getJoursTravaillesLibelleAttribute(): string
    {
        $jours = explode(',', $this->jours_travailles);
        $map = [
            'lundi' => 'Lun',
            'mardi' => 'Mar',
            'mercredi' => 'Mer',
            'jeudi' => 'Jeu',
            'vendredi' => 'Ven',
            'samedi' => 'Sam',
            'dimanche' => 'Dim',
        ];
        return implode(', ', array_map(fn($j) => $map[$j] ?? $j, $jours));
    }

    public function getJoursTravaillesArrayAttribute(): array
    {
        return explode(',', $this->jours_travailles);
    }

    public function scopeByPoste($query, $posteId)
    {
        return $query->where('poste_id', $posteId);
    }

    public function scopeParDefaut($query)
    {
        return $query->where('par_defaut', true);
    }
    /**
     * Site / établissement concerné par cet horaire type
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'site_id', 'ID');
    }
}
