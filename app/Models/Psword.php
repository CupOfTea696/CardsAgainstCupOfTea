<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as IlluminateModel;

class Model extends IlluminateModel
{
    /**
     * Fire the given event for the model.
     *
     * @param  string  $event
     * @param  bool  $halt
     * @return mixed
     */
    protected function fireModelEvent($event, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }
        
        // We will append the names of the class to the event to distinguish it from
        // other model events that are fired, allowing us to listen on each model
        // event set individually instead of catching event for all the models.
        $event = "eloquent.{$event}: ".get_class($this);
        
        $method = $halt ? 'until' : 'fire';
        
        return static::$dispatcher->$method($event, $this);
    }
}
