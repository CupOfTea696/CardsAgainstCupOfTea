<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use CAT\User\Logic;

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
    
    public function join(Request $request, Logic $userLogic)
    {
        $username = $request->input('username');
        
        // check if taken by other joins
        if (in_array($username, $userLogic->online() || User::where('username', $username)->count())) {
            return redirect()->back()->withErrors(['username' => trans('game.username.inuse')]);
        }
        
        $userLogic->register($username);
        
        return redirect()->route('lobby');
    }
}
