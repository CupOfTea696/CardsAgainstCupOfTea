<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Pjax\Middleware\FilterIfPjax as SpatieFilterIfPjax;

class FilterIfPjax extends SpatieFilterIfPjax
{
    /**
     * Easily add extra filters for PJAX requests.
     *
     * @param \Illuminate\Http\Response $response
     * @param \Illuminate\Http\Request  $request
     */
    protected function filter(Response $response, Request $request, Closure $filter)
    {
        $filter($response, $request);
        
        if ($route = $request->route()) {
            $response->header('X-PJAX-Route', $route->getName());
        }
    }
}
