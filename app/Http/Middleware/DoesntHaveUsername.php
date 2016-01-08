<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class DoesntHaveUsername
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // check if join username 
        if (Auth::guard($guard)->check()) {
            return redirect()->route('lobby');
        }
        
        return $next($request);
    }
}
