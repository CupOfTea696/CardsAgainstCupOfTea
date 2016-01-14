<?php namespace App\Ws;

use CupOfTea\TwoStream\Foundation\Ws\Kernel as WsKernel;

class Kernel extends WsKernel
{

    /**
     * The application's global Ws middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'CupOfTea\TwoStream\Cookie\Middleware\DecryptCookies',
        'CupOfTea\TwoStream\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
    ];
    
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => 'App\Http\Middleware\Authenticate',
        'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
    ];
    
}
