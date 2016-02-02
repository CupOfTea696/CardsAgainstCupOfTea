<?php

namespace App\Game\Objects;

use App\Support\SortedObject;

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