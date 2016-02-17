<?php

namespace CAT\Game\Logic;

use Auth;
use Redis;

use App\Models\Game;
use App\Models\User;

use Illuminate\Contracts\Encryption\DecryptException;

class User
{
    protected $user;
    
    public function __construct(User $user = null)
    {
        $this->user = $user ?: Auth::user();
    }
    
    public function join(Game $game, $spectator = false)
    {
        if ($spectator) {
            Redis::sadd('game:' . $game->id . ':spectators', $this->user->id);
        } else {
            Redis::sadd('game:' . $game->id . ':players', $this->user->id);
        }
    }
    
    public function isAuthenticatedForGame(Game $game)
    {
        if (! $game->private) {
            return true;
        }
        
        if ($this->hasValidAuthTokenForGame($game)) {
            $this->refreshAuthToken();
            
            return true;
        }
        
        return $this->authenticateForGame($game);
    }
    
    public function authenticateForGame(Game $game, $password = null)
    {
        if ($game->password == $password || Redis::sismember('game:' . $game->id . ':invited', $this->user->id)) {
            $gameId = $game->id;
            $slug = $game->slug;
            $userId = $this->user->id;
            
            $token = Crypt::encrypt(compact('userId', 'slug', 'gameId'));
            
            Redis::setEx('user:' . $this->user->id . ':game_token', days(), $token);
            
            return true;
        }
        
        return false;
    }
    
    public function refreshUserAuthToken()
    {
        Redis::expire('user:' . $this->user->id . ':game_token', days());
    }
    
    public function setStatus($status)
    {
        // set user status to: playing / away / spectating
    }
    
    protected function hasValidAuthTokenForGame(Game $game)
    {
        if ($token = Redis::get('user:' . $this->user->id . ':game_token')) {
            try {
                extract(Crypt::decrypt($token));
                
                return $this->user->id == $userId && $game->slug === $slug && $game->id == $gameId;
            } catch (DecryptException $e) {
                return false;
            }
        }
        
        return false;
    }
}