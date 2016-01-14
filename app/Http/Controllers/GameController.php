<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    public function lobby()
    {
        $rooms = [];
        
        return view('lobby.index', compact('rooms'));
    }
    
    public function create()
    {
        return view('room.create');
    }
}
