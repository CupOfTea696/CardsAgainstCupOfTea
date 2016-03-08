<?php

namespace CAT\Game;

use Auth;
use Redis;
use Storage;

use App\Models\Game;
use App\Models\User;

use CAT\Game\Logic\User as UserLogic;

use Illuminate\Contracts\Encryption\DecryptException;

class Logic
{
    protected $sets = [];
    
    public function user(User $user = null)
    {
        return new UserLogic($user);
    }
    
    public function createNameFromUsername(User $user = null)
    {
        $user = $user ?: Auth::user();
        $username = $user->username;
        
        return possessive($username) . ' Room';
    }
    
    public function getSets()
    {
        if (! $this->sets && Storage::exists('decks.json')) {
            $sets = json_decode(Storage::get('decks.json'));
            
            foreach ($sets as $id => $set) {
                $this->sets[$id] = $set;
            }
        }
        
        return $this->sets;
    }
    
    public function getSet($id)
    {
        return array_get($this->sets, $id);
    }
    
    public function start()
    {
        // if game.status == paused
            // game.resume
        // else
            // game.new
        // endif
    }
    
    public function stop()
    {
        
    }
    
    public function pause()
    {
        
    }
    
    public static function make()
    {
        return new static;
    }
}