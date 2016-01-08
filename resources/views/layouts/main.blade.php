<!DOCTYPE html>
<html id="{{ studly_case(config('app.site_name')) }}">
<head>
    <title>{{ $site_name }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    
    @include('assets.css')
</head>
<body>
    @include('header')
    
    <main class="container" id="page">
        @yield('page')
    </main>
    
    @include('footer')
    
    @include('assets.js')
</body>
</html>