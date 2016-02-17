<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Game;

use CAT\Game\Logic;

class AuthenticatedForRoom
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
        $logic = Logic::make();
        $game = $request->route('game');
        
        if (! $logic->user()->isAuthenticatedForGame($game)) {
            if (! $game->password) {
                return Flash::errorAlert('game.not_invited')->redirect('lobby');
            }
            
            return redirect('game.authenticate', compact('game'));
        }
        
        return $next($request);
    }
}
