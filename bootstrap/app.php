<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->append(\App\Http\Middleware\SetLocale::class);

        $middleware->alias([
            'siege.access'            => \App\Http\Middleware\SiegeAccessMiddleware::class,
            'block.sellers'           => \App\Http\Middleware\BlockSellers::class,
            'only.sellers'            => \App\Http\Middleware\OnlySellers::class,
            'block.simple.admin.sieges' => \App\Http\Middleware\BlockSimpleAdminSieges::class,
            'block.supervisor'        => \App\Http\Middleware\BlockSupervisor::class,
            'only.supervisor'         => \App\Http\Middleware\OnlySupervisor::class,
            'ensure.active'           => \App\Http\Middleware\EnsureAccountActive::class,
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureAccountActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
