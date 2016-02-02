<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;

class SortedObject implements Arrayable
{
    public function __construct($obj)
    {
        $this->add($obj);
    }
    
    public function add($obj)
    {
        if (is_array($obj)) {
            $obj = (object) $obj;
        }
        
        foreach(get_object_vars($this) as $prop => $v) {
            if (isset($obj->$prop)) {
                $this->$prop = $obj->$prop;
            }
        }
        
        return $this;
    }
    
    public function toArray()
    {
        return array_filter((array) $this);
    }
    
    public static function make($obj)
    {
        return new static($obj);
    }
}
