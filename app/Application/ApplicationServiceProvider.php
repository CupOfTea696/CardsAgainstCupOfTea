<?php

namespace CAT;

use Illuminate\Support\AggregateServiceProvider;

class ApplicationServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        Flash\FlashServiceProvider::class,
        XRedis\XRedisServiceProvider::class,
    ];
}
