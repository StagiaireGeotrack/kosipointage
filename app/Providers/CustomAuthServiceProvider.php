<?php
// app/Providers/CustomAuthServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\Sha1HashProvider;

class CustomAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('sha1hash', function ($app, array $config) {
            return new Sha1HashProvider($app['hash'], $config['model']);
        });
    }
}