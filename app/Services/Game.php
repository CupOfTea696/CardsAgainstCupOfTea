<?php

namespace App\Services;

use Auth;

class Game
{
    public function createRoomNameFromUsername()
    {
        $username = Auth::user()->username;
        
        if (ends_with($username, 's') || ends_with($username, 'x') || ends_with($username, 'z')) {
            $their = "'";
        } else {
            $their = "'s";
        }
        
        return $username . $their . ' Room';
    }
}