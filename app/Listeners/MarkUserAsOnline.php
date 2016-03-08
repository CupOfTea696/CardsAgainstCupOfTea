<?php

namespace App\Listeners;

use App\Events\WebRequestReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkUserAsOnline
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Handle the event.
     *
     * @param  WebRequestReceived  $event
     * @return void
     */
    public function handle(WebRequestReceived $event)
    {
        if ($event->user) {
            
        }
    }
}
