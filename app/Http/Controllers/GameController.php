<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\Models\Room;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    // lobby
    public function lobby()
    {
        $rooms = [];
        
        return view('lobby.index', compact('rooms'));
    }
    
    public function show(Room $room)
    {
        echo var_dump($room);
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
        
        $room = Room::create(Auth::check() ? $request->all() : $request->only(['name', 'slug', 'capacity', 'spec_capacity']));
        
        return redirect()->route('room.show', [$room]);
    }
    
    protected function createSlugFromRequest(Request $request)
    {
        if (! $request->input('name') && ($user = $request->user())) {
            $request->merge([
                'name' => $this->createRoomNameFromUsername($user->username),
            ]);
        }
        
        return str_slug($request->input('name'));
    }
    
    protected function createRoomNameFromUsername($username)
    {
        if (ends_with($username, 's') || ends_with($username, 'x') || ends_with($username, 'z')) {
            $their = "'";
        } else {
            $their = "'s";
        }
        
        return $username . $their . ' Room';
    }
}
