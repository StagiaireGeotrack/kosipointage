<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveWorkflow extends Model
{
    use SoftDeletes;

    protected $table = 'leave_workflows';

    protected $fillable = [
        'leave_type_id',
        'site_id',
        'name',
        'description',
        'steps',
        'is_default',
        'is_customizable',
        'is_active',
        'deleted_at',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_default' => 'boolean',
        'is_customizable' => 'boolean',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    /**
     * Relation avec le site (siège)
     */
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
    }

    /**
     * Relation avec le type de congé
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    /**
     * Relation avec les étapes du workflow
     */
    public function steps()
    {
        return $this->hasMany(LeaveWorkflowStep::class)->orderBy('step_order');
    }

    // ============ ACCESSORS ============

    /**
     * Vérifier si le workflow est global (sans site)
     */
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    /**
     * Récupérer les étapes sous forme de tableau (décodé depuis JSON)
     */
    public function getStepsArrayAttribute(): array
    {
        if (is_array($this->steps)) {
            return $this->steps;
        }
        if (is_string($this->steps)) {
            return json_decode($this->steps, true) ?: [];
        }
        return [];
    }

    /**
     * Compter le nombre d'étapes
     */
    public function getStepsCountAttribute(): int
    {
        return count($this->steps_array);
    }

    /**
     * Obtenir le chemin des étapes (ex: "Manager → RH → Direction → Validé")
     */
    public function getStepsPathAttribute(): string
    {
        $steps = $this->steps_array;
        if (empty($steps)) {
            return 'Aucune étape';
        }
        $labels = [];
        foreach ($steps as $step) {
            $labels[] = $step['label'] ?? $step['role'] ?? 'Étape';
        }
        $labels[] = 'Validé';
        return implode(' → ', $labels);
    }

    // ============ SCOPES ============

    /**
     * Scope : filtre les workflows visibles pour un utilisateur donné.
     * - SuperAdmin voit tout.
     * - Admin simple voit les globaux + ceux de son site.
     */
    public function scopeVisibleForUser($query, $user)
    {
        if (!$user) {
            return $query;
        }

        $isSuperAdmin = $user->IsSuperAdmin ?? false;
        if ($isSuperAdmin) {
            return $query;
        }

        $siteId = $user->SiegeID ?? null;
        if ($siteId) {
            return $query->where(function ($q) use ($siteId) {
                $q->whereNull('site_id')
                  ->orWhere('site_id', $siteId);
            });
        }

        // Si l'utilisateur n'a pas de site, on ne lui montre que les globaux
        return $query->whereNull('site_id');
    }

    /**
     * Scope : actif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : par site
     */
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}