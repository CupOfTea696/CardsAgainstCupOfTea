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
        
        if (App::environment('local', 'development', 'staging')) {
            if (! $hash = config('version.' . $file)) {
                $hash = $get_hash($file);
                
                config(compact('hash'));
            }
        } else {
            $hash = Cache::remember('version.' . $file, config('version.cache_duration', 5), function () use ($get_hash, $file) { return $get_hash($file); });
        }
        
        return asset($file) . '?v=' . $hash;
    }
}

if (! function_exists('layout_version')) {
    function layout_version($layout = 'main') {
        $get_hash = function ($layout) {
            $hash = '';
            $files = [
                public_path('assets/css/app.css'),
                public_path('assets/js/app.js'),
                resource_path('views/layouts/main.blade.php'),
                resource_path('views/partials/header.blade.php'),
                resource_path('views/partials/footer.blade.php'),
            ];
            
            if ($layout != 'main') {
                $files[] =  resource_path('views/layouts/' . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.blade.php');
            }
            
            foreach ($files as $file) {
                $hash .= hash_file('md5', $file);
            }
            
            return hash('md5', $hash);
        };
        
        if (App::environment('local', 'development', 'staging')) {
            if (! $hash = config('version.layout.' . $layout)) {
                $hash = $get_hash($layout);
                
                config(compact('hash'));
            }
        } else {
            $hash = Cache::remember('version.layout.' . $layout, config('version.cache_duration', 5), function () use ($get_hash, $layout) { return $get_hash($layout); });
        }
        
        return $hash;
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

if (! function_exists('is_current_route')) {
    function is_current_route($route)
    {
        if ($route instanceof Illuminate\Routing\Route) {
            $route = $route->getName();
        }
        
        return $route === current_route('name');
    }
}

if (! function_exists('uc_trans')) {
    function uc_trans($id, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucwords(trans($id, $parameters, $domain, $locale));
    }
}

if (! function_exists('ucf_trans')) {
    function ucf_trans($id, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucfirst(trans($id, $parameters, $domain, $locale));
    }
}

if (! function_exists('uc_trans_choice')) {
    function uc_trans_choice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucwords(trans_choice($id, $number, $parameters, $domain, $locale));
    }
}

if (! function_exists('ucf_trans_choice')) {
    function ucf_trans_choice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return ucfirst(trans_choice($id, $number, $parameters, $domain, $locale));
    }
}
