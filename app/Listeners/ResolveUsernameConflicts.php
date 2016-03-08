<?php

namespace App\Listeners;

use CAT\User\Logic;

use App\Models\User;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResolveUsernameConflicts
{
    protected $userLogic;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Logic $userLogic)
    {
        $this->userLogic = $userLogic;
    }
    
    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(User $user)
    {
        $username = $user->username;
        $online_users = $this->userLogic->online();
        
        $pool = '0123456789';
        
        while (in_array($username, $online_users)) {
            $length = rand(1, 5);
            $username = $user->username . substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        }
        
        $this->userLogic->register($username);
    }
}
