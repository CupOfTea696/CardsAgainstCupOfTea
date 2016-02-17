<?php

namespace CAT\Game\Objects;

use CAT\Support\SortedObject;

class Deck extends SortedObject
{
    public $name;
    
    public $description;
    
    public $version;
    
    public $slug;
    
    public $calls;
    
    public $responses;
    
    public $cards;
}