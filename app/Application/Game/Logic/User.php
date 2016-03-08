<?php

namespace CAT\Game\Logic;

use Auth;
use Redis;
use JRedis;

use CAT\User\Logic;

use App\Models\Game;
use App\Models\User;

use Illuminate\Contracts\Encryption\DecryptException;

class User
{
    protected $user;
    
    public function __construct(User $user = null)
    {
        $this->user = $user ?: with(new Logic)->get();
    }
    
    public function join(Game $game, $spectator = false)
    {
        $user_game = Redis::get('user:' . $this->user->id . ':game');
        
        if ($game->id == $user_game) {
            return $this->setStatus($spectator ? 'spectating' : 'playing', $user_game);
        }
        
        $pipe = $this->leave(false, $user_game);
        
        if ($spectator) {
            $pipe->sadd('game:' . $game->id . ':spectators', $this->user);
        } else {
            $pipe->sadd('game:' . $game->id . ':players', $this->user);
        }
        
        $pipe->set('user:' . $this->user->id . ':game', $game->id);
        $pipe->execute();
    }
    
    public function leave($exec = true, $user_game = null)
    {
        $game = $user_game !== null ? $user_game : Redis::get('user:' . $this->user->id . ':game');
        
        if (! $game) {
            if (! $exec) {
                return JRedis::pipeline();
            }
            
            return;
        }
        
        $pipe = JRedis::pipeline();
        
        $pipe->del('user:' . $this->user->id . ':game');
        $pipe->srem('game:' . $game . ':players', $this->user);
        $pipe->srem('game:' . $game . ':spectators', $this->user);
        
        if ($exec) {
            return $pipe->execute();
        }
        
        return $pipe;
    }
    
    public function players(Game $game)
    {
        return JRedis::smembers('game:' . $game->id . ':players');
    }
    
    public function spectators(Game $game)
    {
        return JRedis::smembers('game:' . $game->id . ':spectators');
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
    
    public function setStatus($status, $game = null)
    {
        $game = $game !== null ? $game : Redis::get('user:' . $this->user->id . ':game');
        $pipe = JRedis::pipeline();
        
        if ($status == 'spectating') {
            $pipe->srem('game:' . $game . ':players', $this->user);
            $pipe->sadd('game:' . $game . ':spectators', $this->user);
        } else {
            $pipe->srem('game:' . $game . ':spectators', $this->user);
            $pipe->sadd('game:' . $game . ':players', $this->user);
        }
        
        $pipe->execute();
    }
    
    protected function hasValidAuthTokenForGame(Game $game)
    {
        if ($token = Redis::get('user:' . $this->user->id . ':game_token')) {
            try {
                extract(Crypt::decrypt($token));
                
                return $this->user->id == $userId && $game->slug === $slug && $game->id == $gameId;
            } catch (DecryptException $e) {}
        }
        
        return false;
    }
}
