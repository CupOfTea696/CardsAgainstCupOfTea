<?php

namespace CAT\XRedis\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Redis\Database
 */
class SRedis extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sRedis';
    }
}