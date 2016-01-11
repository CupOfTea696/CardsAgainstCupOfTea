<?php

namespace App\Providers;

use CupOfTea\Package\Package;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use Package;
    
    /**
     * Package Name.
     *
     * @const string
     */
    const PACKAGE = 'CupOfTea/CardsAgainstTea';
    
    /**
     * Package Version.
     *
     * @const string
     */
    const VERSION = '0.0.1';
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share([
            'app_name' => config('app.site_name'),
            'app_version' => $this->version(),
        ]);
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
