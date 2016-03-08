<?php

namespace CAT\User;

use Auth;
use Redis;
use Session;

use App\Models\User;
use App\Models\OnlineUser;

class Logic
{
    protected $user;
    
    protected $session;
    
    public function __construct()
    {
        $this->session = Session::getId();
    }
    
    public function register($username)
    {
        if ($username instanceof User) {
            $username = $username->username;
        }
        
        Redis::setex('online:' . $this->session, minutes(5), $username);
    }
    
    public function ping()
    {
        Redis::expire('online:' . $this->session, minutes(5));
    }
    
    public function get()
    {
        if (! isset($this->user)) {
            if (! Redis::exists()) {
                if (! Auth::check()) {
                    return;
                }
                
                $this->register(Auth::user()->username);
            }
            
            $this->user = with(new OnlineUser)->fill([
                'id' => Session::getId(),
                'username' => Session::get('username'),
            ]);
            
            if (Auth::check()) {
                $this->user->fillFromEloquent(Auth::user());
            } else {
                $this->user->email = strtolower($this->user->username) . '@users.' . $_SERVER['SERVER_NAME'];
            }
        }
        
        return $this->user;
    }
    
    public function leave()
    {
        Redis::del('online:' . $this->session);
    }
    
    public function online()
    {
        $keys = Redis::keys('online:*');
        
        if ($keys) {
            return Redis::mget($keys);
        }
        
        return [];
    }
}
