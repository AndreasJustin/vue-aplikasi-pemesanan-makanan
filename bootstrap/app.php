<?php

use App\Http\Middleware\AblePayOrder;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AbleCreateUser;
use App\Http\Middleware\AbleCreateOrder;
use App\Http\Middleware\AbleFinishOrder;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\AbleCreateUpdateItem;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ableCreateOrder' => AbleCreateOrder::class,
            'ableFinishOrder' => AbleFinishOrder::class,
            'ablePayOrder' => AblePayOrder::class,
            'ableCreateUser' => AbleCreateUser::class,
            'ableCreateUpdateItem' => AbleCreateUpdateItem::class,
        ]);
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
