<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Pjax\Middleware\FilterIfPjax as SpatieFilterIfPjax;

class FilterIfPjax extends SpatieFilterIfPjax
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = parent::handle($request, $next);
        
        if (!$request->pjax() || $response->isRedirection()) {
            return $response;
        }
        
        if ($route = $request->route()) {
            $response->header('X-PJAX-Route', $route->getName());
        }
        
        return $response;
    }
}
