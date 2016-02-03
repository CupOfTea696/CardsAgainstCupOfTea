<?php

namespace App\Game;

use Auth;
use Storage;

class Logic
{
    public $sets = [];
    
    public function __construct()
    {
        if (Storage::exists('decks.json')) {
            $sets = json_decode(Storage::get('decks.json'));
            
            foreach ($sets as $id => $set) {
                $this->sets[$id] = $set;
            }
        }
    }
    
    public function createNameFromUsername()
    {
        $username = Auth::user()->username;
        
        if (ends_with($username, 's') || ends_with($username, 'x') || ends_with($username, 'z')) {
            $their = "'";
        } else {
            $their = "'s";
        }
        
        return $username . $their . ' Room';
    }
    
    public function setUserStatus($status)
    {
        // set user status to: playing / away / spectating
    }
}