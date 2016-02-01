<?php

namespace App\Services;

use Auth;
use Storage;

class GameLogic
{
    public $main = 'cards-against-humanity';
    
    public $locales = [
        'us',
        'ca',
        'uk',
    ];
    
    public $expansions = [];
    
    public function __construct()
    {
        $decks = Storage::get('decks.json');
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