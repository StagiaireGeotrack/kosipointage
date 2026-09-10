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
    
    // Spécifier les colonnes pour created_at et updated_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Définir le champ de mot de passe personnalisé
    protected $passwordName = 'Password_';

    protected $fillable = [
        'Identifiant_email',
        'Password_',
        'IsSuperAdmin',
        'IsSeller',
        'IsManager',
        'IsSupervisor',   // ← AJOUTÉ
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
        'IsSupervisor' => 'boolean',   // ← AJOUTÉ
        'Actived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public $timestamps = false;

    // Relation avec le siège principal
    public function siege()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    // Relation many-to-many avec les sièges accessibles (pour les vendeurs)
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

    // Vérifie si l'utilisateur est un vendeur
    public function isSeller(): bool
    {
        return $this->IsSeller == 1 && $this->IsSuperAdmin == 1;
    }

    // Vérifie si l'utilisateur est un Manager Vendeur
    public function isManagerSeller(): bool
    {
        return $this->IsSeller == 1 && $this->IsSuperAdmin == 1 && $this->IsManager == 1;
    }

    // Vérifie si l'utilisateur est un vrai Super Admin (pas un vendeur)
    public function isTrueSuperAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0;
    }

    // Vérifie si l'utilisateur est un Manager Super Admin
    public function isManagerSuperAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0 && $this->IsManager == 1;
    }

    // Vérifie si l'utilisateur est un Simple Admin
    public function isSimpleAdmin(): bool
    {
        return $this->IsSuperAdmin == 0 && $this->IsSeller == 0;
    }

    public function isSupervisor(): bool
    {
        return $this->IsSupervisor == 1 && $this->IsSuperAdmin == 0 && $this->IsSeller == 0;
    }

    // Vérifie si l'utilisateur est un Manager Simple Admin
    public function isManagerSimpleAdmin(): bool
    {
        return $this->IsSuperAdmin == 0 && $this->IsSeller == 0 && $this->IsManager == 1;
    }

    public function getEmailForPasswordReset()
    {
        return $this->Identifiant_email;
    }

    public function findForPassport($username)
    {
        return $this->where('Identifiant_email', $username)->first();
    }

    /**
     * Récupère tous les IDs des sièges accessibles pour cet utilisateur
     * ⚠️ CRITIQUE : Utiliser withoutGlobalScope pour éviter la boucle infinie
     */
    public function getSiegeIdsAccessibles(): array
    {
        // Vrai Super Admin : accès à tous les sièges
        if ($this->isTrueSuperAdmin()) {
            return EntrepriseSiege::withoutGlobalScope(SiegeScope::class)
                ->pluck('ID')
                ->toArray();
        }

        // Vendeur : accès aux sièges assignés dans seller_sieges
        if ($this->isSeller()) {
            return $this->sellerSieges()
                ->withoutGlobalScope(SiegeScope::class)
                ->pluck('Entreprises_sieges.ID')
                ->toArray();
        }

        // Simple Admin : accès uniquement à son siège
        if ($this->isSimpleAdmin()) {
            return $this->SiegeID ? [$this->SiegeID] : [];
        }

        return [];
    }

    // Relation avec les services supervisés
    public function supervisorServices()
    {
        return $this->belongsToMany(
            Department::class,
            'supervisor_services',
            'admin_id',
            'service_id',
            'ID',
            'id'
        );
    }

    // Vérifie si l'utilisateur a accès à un siège spécifique
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

    // =========================================================
    // NOUVELLES MÉTHODES POUR L'AFFICHAGE DU RÔLE
    // =========================================================

    /**
     * Retourne le libellé du rôle de l'utilisateur
     */
    public function getRoleLabel(): string
    {
        if ($this->IsSuperAdmin && $this->IsSeller) {
            return 'Vendeur Super Admin';
        }
        if ($this->IsSuperAdmin) {
            return 'Super Admin';
        }
        if ($this->IsSeller) {
            return 'Vendeur';
        }
        if ($this->IsSupervisor) {
            return 'Responsable de service';
        }
        if ($this->IsManager) {
            return 'Manager';
        }
        return 'Utilisateur';
    }

    /**
     * Retourne la classe CSS Bootstrap pour le badge du rôle
     */
    public function getRoleBadgeClass(): string
    {
        if ($this->IsSuperAdmin) {
            return 'bg-danger';
        }
        if ($this->IsSeller) {
            return 'bg-warning text-dark';
        }
        if ($this->IsSupervisor) {
            return 'bg-info';
        }
        if ($this->IsManager) {
            return 'bg-success';
        }
        return 'bg-secondary';
    }
}