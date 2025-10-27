<?php
// app/Extensions/Sha1UserProvider.php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class Sha1UserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        // Récupérer le mot de passe en clair
        $plain = $credentials['password'];

        // Comparer avec le mot de passe haché en SHA1
        return $user->getAuthPassword() === sha1($plain);
    }
}