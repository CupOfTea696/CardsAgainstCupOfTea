<?php

if (! function_exists('first')) {
    function first($array)
    {
        return is_array($array) && reset($array) ? reset($array) : null;
    }
}

if (! function_exists('version')) {
    function version($file)
    {
        $get_hash = function ($file) {
            return hash_file('md5', public_path($file));
        };
        
        if (App::environment('local', 'staging')) {
            if (! $hash = config('version.' . $file)) {
                $hash = $get_hash($file);
                
                config(compact('hash'));
            }
        } else {
            $hash = Cache::remember('version.' . $file, config('version.cache_duration', 5), $get_hash($file));
        }
        
        return asset($file) . '?v=' . $hash;
    }
}

if (! function_exists('current_route')) {
    function current_route($property = null)
    {
        $route = Request::route();
        
        switch ($property) {
            case 'name':
                return $route->getName();
        }
        
        return $route;
    }
}

if (! function_exists('uc_trans')) {
    function uc_trans($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucwords(trans($id, $parameters, $domain, $locale));
    }
}

if (! function_exists('ucf_trans')) {
    function ucf_trans($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucfirst(trans($id, $parameters, $domain, $locale));
    }
}
