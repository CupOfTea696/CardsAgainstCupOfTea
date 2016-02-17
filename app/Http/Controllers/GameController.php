<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use Illuminate\Http\Request;

use App\Models\Game;
use App\Http\Requests;
use CAT\Game\Logic;

class GameController extends Controller
{
    protected $logic;
    
    public function __construct(Logic $logic)
    {
        $this->logic = $logic;
    }
    
    // lobby
    public function lobby()
    {
        $games = Game::all();
        
        return view('lobby.index', compact('games'));
    }
    
    // game.show
    public function show(Request $request, Game $game)
    {
        $this->logic->user()->join($game, $request->input('join_as'));
        
        return view('game.show', compact('game'));
    }
    
    // game.password
    public function passwordPrompt(Game $game)
    {
        return view('game.password', compact('game'));
    }
    
    // game.auth
    public function authenticate(Game $game)
    {
        
    }
    
    // game.create
    public function create()
    {
        return view('game.create');
    }
    
    // game.store
    public function store(Request $request)
    {
        $request->merge([
            'slug' => $this->createSlugFromRequest($request),
        ]);
        
        $v = Validator::make($request->all(), [
            'slug' => 'required|min:3|unique:Games',
            'capacity' => 'required|integer|min:' . config('game.capacity.min') . '|max:' . config('game.capacity.max'),
            'spec_capacity' => 'required|integer|min:' . config('game.spec_capacity.min') . '|max:' . config('game.spec_capacity.max'),
        ]);
        $v->sometimes('password', 'min:6', function() {
            return Auth::check();
        });
        
        $this->validateWith($v);
        
        if (Auth::check()) {
            $data = $request->all();
            
            if ($data['password']) {
                $data['private'] = true;
            }
        } else {
            $data = $request->only(['name', 'slug', 'capacity', 'spec_capacity']);
        }
        
        $game = Game::create($data);
        
        return redirect()->route('game.show', [$game]);
    }
    
    protected function createSlugFromRequest(Request $request)
    {
        if (! $request->input('name') && ($user = $request->user())) {
            $request->merge([
                'name' => $this->logic->createRoomNameFromUsername(),
            ]);
        }
        
        return str_slug($request->input('name'));
    }
}
