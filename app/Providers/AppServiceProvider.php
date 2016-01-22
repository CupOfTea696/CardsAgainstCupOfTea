<?php

namespace App\Providers;

use Blade;

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
        
        Counter::registerBlade();
        
        Blade::directive('counteach', function($expression) {
            return preg_replace('/\((\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+as\s+((?1))\)/', '<?php Counter::start($1); foreach ($1 as $2): ?>', $expression);
        });
        
        Blade::directive('endcounteach', function($expression) {
            return '<?php Counter::tick(); endforeach; ?>';
        });
        
        Blade::directive('first', function() {
            return '<?php if(Counter::first()): ?>';
        });
        
        Blade::directive('index', function($expression) {
            return "<?php if(Counter::index{$expression}): ?>";
        });
        
        Blade::directive('last', function() {
            return '<?php if(Counter::first()): ?>';
        });
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
