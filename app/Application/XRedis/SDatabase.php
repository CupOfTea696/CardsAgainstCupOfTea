<?php

namespace CAT\XRedis;

class JDatabase extends Database
{
    protected function encode($v)
    {
        return serialize($v);
    }
    
    protected function decode($v)
    {
        return unserialize($v);
    }
}
