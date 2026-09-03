<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRole extends Model
{
    protected $fillable = ['site_id', 'name', 'label', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    /**
     * Relation avec le site (siège)
     */
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
    }

    // ============ ACCESSORS ============

    /**
     * Vérifier si le rôle est global (sans site)
     */
    public function isGlobal(): bool
    {
        return is_null($this->site_id);
    }

    /**
     * Obtenir le nom du site (ou "Global" si null)
     */
    public function getSiteNameAttribute(): string
    {
        return $this->site ? $this->site->Nom : 'Global';
    }

    /**
     * Obtenir le label avec le site pour l'affichage
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->label . ($this->isGlobal() ? ' (Global)' : ' (' . $this->site_name . ')');
    }

    // ============ SCOPES ============

    /**
     * Scope : rôles visibles pour un site (globaux + site)
     */
    public function scopeForSite($query, $siteId)
    {
        return $query->where(function ($q) use ($siteId) {
            $q->where('site_id', $siteId)
              ->orWhereNull('site_id');
        });
    }

    /**
     * Scope : rôles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : rôles inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope : rôles globaux
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('site_id');
    }

    /**
     * Scope : rôles par site
     */
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // ============ MÉTHODES ============

    /**
     * Vérifier si le rôle est utilisé dans des workflows ou validateurs
     */
    public function isUsed(): bool
    {
        $usedInWorkflows = LeaveWorkflow::where('steps', 'LIKE', '%"' . $this->name . '"%')->exists();
        $usedInValidators = LeaveValidator::where('role', $this->name)->exists();
        return $usedInWorkflows || $usedInValidators;
    }

    /**
     * Vérifier si le rôle est critique (ne peut pas être supprimé)
     */
    public function isCritical(): bool
    {
        return in_array($this->name, ['manager', 'rh', 'drh', 'direction']);
    }
}