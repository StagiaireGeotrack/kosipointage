<?php
// app/Models/Administration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Scopes\SiegeScope;

class Administration extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'administration';
    protected $primaryKey = 'ID';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $passwordName = 'Password_';

    protected $fillable = [
        'Identifiant_email',
        'Password_',
        'IsSuperAdmin',
        'IsSeller',
        'IsManager',
        'IsSupervisor',
        'IsMaster',
        'SiegeID',
        'Actived',
        'deleted',
    ];

    protected $hidden = [
        'Password_',
        'remember_token',
    ];

    protected $casts = [
        'IsSuperAdmin' => 'boolean',
        'IsSeller' => 'boolean',
        'IsManager' => 'boolean',
        'IsSupervisor' => 'boolean',
        'IsMaster' => 'boolean',
        'Actived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public $timestamps = false;

    // ============ RELATIONS ============
    public function siege()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    public function sellerSieges()
    {
        return $this->belongsToMany(
            EntrepriseSiege::class,
            'seller_sieges',
            'SellerID',
            'SiegeID',
            'ID',
            'ID'
        )->withPivot('CreatedAt');
    }

    /**
     * Relation Supervisor ↔ Services (départements)
     */
    public function supervisorServices()
    {
        return $this->belongsToMany(
            Department::class,
            'supervisor_services',
            'admin_id',
            'service_id',
            'ID',
            'id'
        )->withPivot('created_at', 'created_by');
    }

    // ============ MÉTHODES DE RÔLE (EXISTANTES — NE PAS MODIFIER) ============
    public function isSeller(): bool
    {
        return $this->IsSeller == 1;
    }

    public function isManagerSeller(): bool
    {
        return $this->IsSeller == 1 && $this->IsSuperAdmin == 1 && $this->IsManager == 1;
    }

    public function isTrueSuperAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0;
    }

    public function isManagerSuperAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0 && $this->IsManager == 1;
    }

    public function isSimpleAdmin(): bool
    {
         return $this->IsSuperAdmin == 0
            && $this->IsSeller == 0
            && $this->IsSupervisor == 0;
    }

    public function isManagerSimpleAdmin(): bool
    {
        return $this->IsSuperAdmin == 0 && $this->IsSeller == 0 && $this->IsManager == 1;
    }

    // ============ NOUVELLES MÉTHODES — RÔLE SUPERVISOR ============

    /**
     * Vérifie si l'utilisateur est un Responsable de service
     */
    public function isSupervisor(): bool
    {
        return $this->IsSupervisor == 1
            && $this->IsSuperAdmin == 0
            && $this->IsSeller == 0
            && $this->IsManager == 0;
    }

        /**
     * Vérifie si l'utilisateur est un Master (adjoint du Simple Admin)
     */
    public function isMaster(): bool
    {
        return $this->IsMaster == 1
            && $this->IsManager == 1
            && $this->IsSuperAdmin == 0
            && $this->IsSeller == 0
            && $this->IsSupervisor == 0;
    }

    /**
     * Vérifie si l'utilisateur est un Simple Admin PUR (pas Master)
     * Utile pour les actions que seul le Simple Admin peut faire (créer Master, modifier Simple Admin)
     */
    public function isSimpleAdminPure(): bool
    {
        return $this->IsManager == 1
            && $this->IsMaster == 0
            && $this->IsSuperAdmin == 0
            && $this->IsSeller == 0
            && $this->IsSupervisor == 0;
    }

    /**
     * Vérifie si l'utilisateur est un Simple Admin au sens du nouveau modèle
     * (IsManager=1, sans les autres flags)
     */
    public function isSimpleAdminStrict(): bool
    {
        return $this->IsManager == 1
            && $this->IsSuperAdmin == 0
            && $this->IsSeller == 0
            && $this->IsSupervisor == 0;
    }

    /**
     * Vérifie si l'utilisateur est un Revendeur au sens du nouveau modèle
     */
    public function isSellerStrict(): bool
    {
        return $this->IsSeller == 1
            && $this->IsSuperAdmin == 0
            && $this->IsManager == 0
            && $this->IsSupervisor == 0;
    }

    // ============ MÉTHODES EXISTANTES (conservées) ============
    public function getEmailForPasswordReset()
    {
        return $this->Identifiant_email;
    }

    public function findForPassport($username)
    {
        return $this->where('Identifiant_email', $username)->first();
    }

    public function getSiegeIdsAccessibles(): array
    {
        if ($this->isTrueSuperAdmin()) {
            return EntrepriseSiege::withoutGlobalScope(SiegeScope::class)
                ->pluck('ID')
                ->toArray();
        }

        if ($this->isSeller()) {
            return $this->sellerSieges()
                ->withoutGlobalScope(SiegeScope::class)
                ->pluck('Entreprises_sieges.ID')
                ->toArray();
        }

        // ✅ Supervisor et Simple Admin : uniquement leur propre siège
        if ($this->isSupervisor() || $this->isSimpleAdmin()) {
            return $this->SiegeID ? [$this->SiegeID] : [];
        }

        return [];
    }

    public function hasAccessToSiege(int $siegeId): bool
    {
        return in_array($siegeId, $this->getSiegeIdsAccessibles());
    }

    public function getAuthPassword()
    {
        return $this->attributes['Password_'];
    }

    public function isAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0;
    }

    /**
     * Retourne les IDs des services affectés (pour un Supervisor)
     */
    public function getSupervisorServiceIds(): array
    {
        if (!$this->isSupervisor()) {
            return [];
        }

        return $this->supervisorServices()
            ->pluck('departments.id')
            ->toArray();
    }
}
