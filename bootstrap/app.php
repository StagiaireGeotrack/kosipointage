<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->append(\App\Http\Middleware\SetLocale::class);

        $middleware->alias([
            'siege.access' => \App\Http\Middleware\SiegeAccessMiddleware::class,
            'block.sellers' => \App\Http\Middleware\BlockSellers::class,
            'only.sellers' => \App\Http\Middleware\OnlySellers::class,
            'block.simple.admin.sieges' => \App\Http\Middleware\BlockSimpleAdminSieges::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
