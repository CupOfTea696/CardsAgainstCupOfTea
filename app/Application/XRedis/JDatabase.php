<?php

namespace CAT\XRedis;

class JDatabase extends Database
{
    protected function encode($v)
    {
        return json_encode($v);
    }
    
    protected function decode($v)
    {
        return json_decode($v);
    }
}
