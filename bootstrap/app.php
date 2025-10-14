<?php

use App\Http\Middleware\Adminauth;
use App\Http\Middleware\Clearmailsession;
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
        $middleware->alias([
          "adminauth"=>Adminauth::class,
          'mail_token_refresh'=>Clearmailsession::class,
      
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
