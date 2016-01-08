<?php

namespace App\Providers;

use Log;
use App\Events\Eloquent;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
    ];
    
    protected $listen_eloquent = [
        'App\Models\User::created' => [
            'App\Listeners\UserCreatedListener',
        ],
    ];
    
    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        $this->listen = array_merge($this->listen, $this->listen_eloquent);
        
        parent::boot($events);
        
        $events->listen('eloquent.*', function ($model) use ($events) {
            $event_type = first(explode(': ', last(explode('.', $events->firing()))));
            $event = get_class($model) . '::' . $event_type;
            
            return $events->fire($event, $model);
        });
    }
    
    public function listens()
    {
        $listens = $this->listen;
        $listens['App\Events\Event'] = [];
        
        foreach ($this->listen_eloquent as $event => $listeners) {
            $listens['App\Events\Event'] = array_merge($listens['App\Events\Event'], $listeners);
        }
        
        return $listens;
    }
}
