<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planning extends Model
{
    use SoftDeletes;

    protected $table = 'plannings';

    protected $fillable = [
        'siege_id',
        'service_id',
        'poste_id',
        'date_debut_semaine',
        'date_fin_semaine',
        'nom',
        'statut',
        'cree_par',
        'valide_par',
        'valide_le',
        'publie_le',
        'commentaire',
    ];

    protected $casts = [
        'date_debut_semaine' => 'date',
        'date_fin_semaine' => 'date',
        'valide_le' => 'datetime',
        'publie_le' => 'datetime',
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

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PlanningDetail::class);
    }

    public function getLibelleSemaineAttribute(): string
    {
        return 'Semaine du ' . $this->date_debut_semaine->format('d/m/Y') . ' au ' . $this->date_fin_semaine->format('d/m/Y');
    }

    public function getNbEmployesAttribute(): int
    {
        return $this->details()->distinct('employe_id')->count();
    }

    public function getNbCreneauxAttribute(): int
    {
        return $this->details()->count();
    }

    public function getLibelleStatutAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'genere' => 'Généré',
            'valide' => 'Validé',
            'publie' => 'Publié',
            'archive' => 'Archivé',
            default => $this->statut,
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'secondary',
            'genere' => 'info',
            'valide' => 'success',
            'publie' => 'primary',
            'archive' => 'dark',
            default => 'secondary',
        };
    }

    public function scopeBySiege($query, $siegeId)
    {
        return $query->where('siege_id', $siegeId);
    }

    public function scopeByService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }
}
