<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use CAT\User\Logic;

class HasUsername
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
        $userLogic = new Logic;
        
        if (! $userLogic->get()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('home'));
            }
        }
        
        return $next($request);
    }
}
