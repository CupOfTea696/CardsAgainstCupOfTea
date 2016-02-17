@extends('layouts.main')

@section('page')
    <header class="hero text-center">
        <h1>{{ trans('lobby.welcome.heading') }}</h1>
        <p class="lead">
            {{ trans('lobby.welcome.message') }}
        </p>
    </header>
    <main class="grid --no-grow">
        <div class="grid/row">
        @count ($games as $game)
            @include('lobby.game')
            
            @if(Counter::even() && ! Counter::last())
                </div>
                <div class="grid/row">
            @endif
        @endcount
        </div>
    </main>
@stop
