<?php
// app/Models/Administration.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administration extends Authenticatable
{
    use Notifiable;

    protected $table = 'administration';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    
    // Spécifier les colonnes pour created_at et updated_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Définir le champ de mot de passe personnalisé
    protected $passwordName = 'Password_';
    
    protected $fillable = [
        'Identifiant_email',
        'Password_',
        'IsSuperAdmin',
        'SiegeID',
        'Actived'
    ];
    
    protected $hidden = [
        'Password_',
        'remember_token',
    ];
    
    protected $casts = [
        'IsSuperAdmin' => 'boolean',
        'Actived' => 'boolean',
    ];
    
    // Mutateur pour le mot de passe
    public function setPasswordAttribute($value)
    {
        $this->attributes['Password_'] = sha1($value);
    }
    
    // Méthode nécessaire pour l'authentification Laravel
    public function getAuthPassword()
    {
        return $this->attributes['Password_'];
    }
    
    // Méthode pour l'authentification avec le bon champ d'email
    public function findForPassport($username)
    {
        return $this->where('Identifiant_email', $username)->first();
    }
    
    public function getEmailForPasswordReset()
    {
        return $this->Identifiant_email;
    }
    
    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }
}