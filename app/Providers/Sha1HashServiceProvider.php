<?php
// app/Providers/Sha1HashServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\AuthManager;
use App\Extensions\Sha1UserProvider;

class Sha1HashServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make(AuthManager::class)->provider('sha1hash', function ($app, $config) {
            return new Sha1UserProvider($app['hash'], $config['model']);
        });
    }
}