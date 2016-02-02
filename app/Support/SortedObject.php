<?php

namespace App\Support;

class SortedObject
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
            $this->$prop = $obj->$prop;
        }
        
        return $this;
    }
}