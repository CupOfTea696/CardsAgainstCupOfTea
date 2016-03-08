<?php

namespace App\Events;

use CAT\User\Logic;

use App\Events\Event;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebRequestReceived extends Event
{
    use SerializesModels;

    public $request;
    
    public $user;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = with(new Logic)->get();
    }
}
