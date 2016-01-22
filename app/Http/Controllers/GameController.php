<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\Models\Room;
use App\Http\Requests;
use App\Services\Game;

class GameController extends Controller
{
    protected $game;
    
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    
    // lobby
    public function lobby()
    {
        $rooms = Room::all();
        
        return view('lobby.index', compact('rooms'));
    }
    
    public function show(Room $room)
    {
        return view('room.show', compact('room'));
    }
    
    // room.create
    public function create()
    {
        return view('room.create');
    }
    
    // room.store
    public function store(Request $request)
    {
        $request->merge([
            'slug' => $this->createSlugFromRequest($request),
        ]);
        
        $this->sometimes('password', 'min:6', function() {
            return Auth::check();
        })->validate($request, [
            'slug' => 'required|min:3|unique:Rooms',
            'capacity' => 'required|integer|min:' . config('game.capacity.min') . '|max:' . config('game.capacity.max'),
            'spec_capacity' => 'required|integer|min:' . config('game.spec_capacity.min') . '|max:' . config('game.spec_capacity.max'),
        ]);
        
        if (Auth::check()) {
            $data = $request->all();
            
            if ($data['password']) {
                $data['private'] = true;
            }
        } else {
            $data = $request->only(['name', 'slug', 'capacity', 'spec_capacity']);
        }
        
        $room = Room::create($data);
        
        return redirect()->route('room.show', [$room]);
    }
    
    protected function createSlugFromRequest(Request $request)
    {
        if (! $request->input('name') && ($user = $request->user())) {
            $request->merge([
                'name' => $this->game->createRoomNameFromUsername(),
            ]);
        }
        
        return str_slug($request->input('name'));
    }
}
