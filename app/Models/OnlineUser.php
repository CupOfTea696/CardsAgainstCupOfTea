<?php

namespace App\Models;

use Jenssegers\Model\Model;

class OnlineUser extends Model
{
    protected $eloquent;
    
    public function hasEloquent()
    {
        return isset($this->eloquent);
    }
    
    public function getEloquent()
    {
        return $this->eloquent;
    }
    
    public function fillFromEloquent(User $user)
    {
        $this->eloquent = $user;
        $attributes = $user->toArray();
        
        if (isset($this->username)) {
            unset($attributes['username']);
        }
        
        $this->fill($attributes);
    }
}
