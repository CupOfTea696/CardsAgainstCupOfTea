<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('doesnt.have.username');
    }
    
    public function index()
    {
        return view('index');
    }
    
    public function join(Request $request)
    {
        $username = $request->input('username');
        
        // check if taken by other joins
        if (User::where('username', $username)->count()) {
            return redirect()->back()->withErrors(['username' => trans('game.username.inuse')]);
        }
        
        return redirect()->route('lobby');
    }
}
