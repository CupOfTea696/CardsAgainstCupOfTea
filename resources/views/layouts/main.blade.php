<!DOCTYPE html>
<html id="{{ studly_case($app_name) }}">
<head>
    <title>{{ $app_name }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="application-name" content="{{ $app_name }}">
    <meta name="author" content="CupOfTea696">
    <meta name="version" content="{{ $app_version }}">
    @section('layout.version')
        <meta http-equiv="x-pjax-version" content="{{ layout_version() }}">
    @stop
    @yield('layout.version')
    
    @include('assets.css')
</head>
<body>
    <div id="container">
        @include('partials.header')
        
        <main class="{{ '@' . str_replace('.', '/', current_route('name')) }} container" id="page">
            @yield('page')
        </main>
        
        @include('partials.footer')
    </div>
    
    @include('assets.js')
</body>
</html>
