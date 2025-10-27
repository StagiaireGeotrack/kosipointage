<?php
// app/Services/Sha1HashProvider.php

namespace App\Services;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class Sha1HashProvider extends EloquentUserProvider
{
    /**
     * Valider les informations d'identification d'un utilisateur.
     *
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        
        // Utiliser SHA1 pour hasher le mot de passe et comparer
        return $user->getAuthPassword() === sha1($plain);
    }
}