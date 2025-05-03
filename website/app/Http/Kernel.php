<?php

namespace App\Http;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected array $middleware = [
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    ];

    /**
     * The application's bootstrappers.
     */
    protected array $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * The application's route middleware groups.
     */
    protected array $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected array $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'isadmin' => \App\Http\Middleware\IsAdmin::class,  // Updated alias for consistency
        'iskaryawan' => \App\Http\Middleware\IsKaryawan::class,  // Updated alias for consistency
    ];
}
