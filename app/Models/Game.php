<?php

namespace App\Models;

use Crypt;
use Redis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;

class Game extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Games';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'capacity',
        'spec_capacity',
        'private',
        'password',
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'permanent',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'players',
        'goal',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'private' => 'bool',
        'permanent' => 'bool',
    ];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return false;
        }
    }
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }
    
    public function getPlayerCountAttribute()
    {
        return Redis::scard('game:' . $this->id . ':players');
    }
    
    public function getSpectatorCountAttribute()
    {
        return Redis::scard('game:' . $this->id . ':spectators');
    }
    
    public function getGoalAttribute()
    {
        return 8;
    }
    
    public function getStatusAttribute()
    {
        return 'Not Playing';
    }
}
