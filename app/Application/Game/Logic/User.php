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
        $user_game = Redis::get('user:' . $this->user->id . ':game');
        
        if ($game->id == $user_game) {
            return $this->setStatus($spectator ? 'playing' : 'spectation', $user_game);
        }
        
        $pipe = $this->leave(false, $user_game);
        
        if ($spectator) {
            $pipe->sadd('game:' . $game->id . ':spectators', $this->user->id);
        } else {
            $pipe->sadd('game:' . $game->id . ':players', $this->user->id);
        }
        
        $pipe->set('user:' . $this->user->id . ':game', $game->id);
        $pipe->execute();
    }
    
    public function leave($exec = true, $user_game = null)
    {
        $game = $user_game !== null ? $user_game : Redis::get('user:' . $this->user->id . ':game');
        
        if (! $game) {
            if (! $exec) {
                return Redis::pipeline();
            }
            
            return;
        }
        
        $pipe = Redis::pipeline();
        
        $pipe->del('user:' . $this->user->id . ':game');
        $pipe->srem('game:' . $game . ':players', $this->user->id);
        $pipe->srem('game:' . $game . ':spectators', $this->user->id);
        
        if ($exec) {
            return $pipe->execute();
        }
        
        return $pipe;
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
        $game = $user_game !== null ? $user_game : Redis::get('user:' . $this->user->id . ':game');
        $pipe = Redis::pipeline();
        
        if ($status == 'spectating') {
            $pipe->srem('game:' . $game . ':players', $this->user->id);
            $pipe->sadd('game:' . $game . ':spectators', $this->user->id);
        } else {
            $pipe->srem('game:' . $game . ':spectators', $this->user->id);
            $pipe->sadd('game:' . $game . ':players', $this->user->id);
        }
        
        $pipe->execute();
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