<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    /**
     * Create a new lobby controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('has.username');
    }
    
    public function lobby()
    {
        return view('lobby');
    }
}
