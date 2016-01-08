<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class Eloquent extends Event
{
    use SerializesModels;
    
    public $__model;
    
    /**
     * Create a new event instance.
     *
     * @param  Podcast  $podcast
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->__model = $model;
    }
    
    public function __get($key)
    {
        return $this->__model->$key;
    }
    
    public function __set($key, $value)
    {
        $this->__model->$key = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->__model->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->__model->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->__model->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->__model->$offset);
    }
    
    public function __isset($key)
    {
        return isset($this->__model->$key);
    }
    
    public function __unset($key)
    {
        unset($this->__model->$key);
    }
    
    public function __call($method, $args)
    {
        switch (count($args)) {
            case 0:
                return $this->__model->$method();
            case 1:
                return $this->__model->$method($args[0]);
            case 2:
                return $this->__model->$method($args[0], $args[1]);
            case 3:
                return $this->__model->$method($args[0], $args[1], $args[2]);
            case 4:
                return $this->__model->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$this->__model, $method], $args);
        }
    }
    
    public function __toString()
    {
        return $this->__model->toJson();
    }
}