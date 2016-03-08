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
        'App\Events\WebRequestReceived' => [
            'App\Listeners\MarkUserAsOnline'
        ],
    ];
    
    protected $listen_eloquent = [
        'App\Models\Fire::created' => [
            'App\Listeners\ResolveUsernameConflicts',
            'App\Listeners\EmailSignedUpConfirmation',
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
        $listens = $this->listen;
        $this->listen = array_merge($this->listen, $this->listen_eloquent);
        
        parent::boot($events);
        
        $events->listen('eloquent.*', function ($model) use ($events) {
            $event_type = first(explode(': ', last(explode('.', $events->firing()))));
            $event = get_class($model) . '::' . $event_type;
            
            return $events->fire($event, $model);
        });
        
        $this->listen = $listens;
    }
    
    public function listens()
    {
        $listens = $this->listen;
        
        foreach ($this->listen_eloquent as $event => $listeners) {
            $model = explode('::', $event)[0];
            
            if (array_key_exists($model, $listens)) {
                $listens[$model] = array_merge($listens[$model], $listeners);
            } else {
                $listens[$model] = $listeners;
            }
        }
        
        return $listens;
    }
}
