<?php

namespace CAT\XRedis;

use Illuminate\Redis\Database as IlluminateDatabase;

abstract class Database extends IlluminateDatabase
{
    private $storeEncoded = [
        'getset' => ['from' => 1],
        'linsert' => ['from' => 3],
        'lpush' => ['from' => 1],
        'lpushx' => ['from' => 1],
        'lrem' => ['from' => 2],
        'lset' => ['from' => 2],
        'psetex' => ['from' => 2],
        'rpush' => ['from' => 1],
        'rpushx' => ['from' => 1],
        'sadd' => ['from' => 1],
        'set' => ['from' => 1, 'to' => 1],
        'setex' => ['from' => 2],
        'setrange' => ['from' => 1],
        'setex' => ['from' => 2],
        'smove' => ['from' => 2],
        'srem' => ['from' => 1],
        'zrank' => ['from' => 1],
        'zrem' => ['from' => 1],
        'zrevrank' => ['from' => 1],
        'zscore' => ['from' => 1],
    ];
    
    private $returnDecoded = [
        'get',
        'getset',
        'lindex',
        'lpop',
        'lrange',
        'mget',
        'rpop',
        'sdiff',
        'sinter',
        'smembers',
        'spop',
        'srandmember',
        'sunion',
    ];
    
    // getset
    
    /**
     * Run a command against the Redis database.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    final public function command($method, array $args = [])
    {
        if (in_array(strtolower($method), array_keys($this->storeEncoded))) {
            $values = $this->storeEncoded[strtolower($method)];
            
            $from = array_get($values, 'from', 0);
            $to = array_get($values, 'to', max(0, count($args) -1));
            
            for ($i = $from; $i <= $to; $i++) {
                $args[$i] = $this->encode($args[$i]);
            }
        }
        
        if ($method == 'mset' || $method == 'msetnx') {
            if (count($args) === 1 && is_array($args[0])) {
                foreach ($args[0] as $k => $v) {
                    $args[0][$k] = $this->encode($v);
                }
            } else {
                foreach ($args as $k => $v) {
                    if ($k % 2 != 0) {
                        $args[$k] = $this->encode($v);
                    }
                }
            }
        }
        
        if ($method == 'zadd') {
            if (is_array(end($args))) {
                foreach (array_pop($args) as $k => $v) {
                    $args[][$k] = $this->encode($v);
                }
            } else {
                foreach ($arguments as $k => $v) {
                    if ($k !== 0 && $k % 2 == 0) {
                        $arguments[$k] = $this->encode($v);
                    }
                }
            }
        }
        
        if (in_array(strtolower($method), $this->returnDecoded)) {
            $result = parent::command($method, $args);
            
            if (is_array($result)) {
                return array_map(function($value) {
                    return $this->decode($value);
                }, $result);
            }
            
            return $this->decode($result);
        }
        
        return parent::command($method, $args);
    }
    
    abstract protected function encode($v);
    
    abstract protected function decode($v);
}