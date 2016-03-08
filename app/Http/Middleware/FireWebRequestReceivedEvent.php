<?php

namespace App\Http\Middleware;

use Event;
use Closure;

use App\Events\WebRequestReceived;

class FireWebRequestReceivedEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Event::fire(new WebRequestReceived($request));
        
        return $next($request);
    }
}
