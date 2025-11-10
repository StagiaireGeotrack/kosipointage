<?php
// app/Models/Administration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'SiegeID',
        'Actived',
    ];

    protected $hidden = [
        'Password_',
        'remember_token',
    ];
    
    protected $casts = [
        'IsSuperAdmin' => 'boolean',
        'Actived' => 'boolean',
        'IsSeller' => 'boolean',
        'created_at' => 'datetime'
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

    // Vérifie si l'utilisateur est un vrai Super Admin (pas un vendeur)
    public function isTrueSuperAdmin(): bool
    {
        return $this->IsSuperAdmin == 1 && $this->IsSeller == 0;
    }

    // Vérifie si l'utilisateur est un Simple Admin
    public function isSimpleAdmin(): bool
    {
        return $this->IsSuperAdmin == 0 && $this->IsSeller == 0;
    }

    public function getEmailForPasswordReset()
    {
        return $this->Identifiant_email;
    }

    public function findForPassport($username)
    {
        return $this->where('Identifiant_email', $username)->first();
    }

    // Récupère tous les IDs des sièges accessibles pour cet utilisateur
    public function getSiegeIdsAccessibles(): array
    {
        // Vrai Super Admin : accès à tous les sièges
        if ($this->isTrueSuperAdmin()) {
            return EntrepriseSiege::pluck('ID')->toArray();
        }

        // Vendeur : accès aux sièges assignés dans seller_sieges
        if ($this->isSeller()) {
            return $this->sellerSieges()->pluck('Entreprises_sieges.ID')->toArray();
        }

        // Simple Admin : accès uniquement à son siège
        if ($this->isSimpleAdmin()) {
            return $this->SiegeID ? [$this->SiegeID] : [];
        }

        return [];
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
}