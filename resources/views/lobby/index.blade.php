@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>{{ trans('lobby.welcome.heading') }}</h1>
        <p class="lead">
            {{ trans('lobby.welcome.message') }}
        </p>
    </header>
    <main class="grid --no-grow">
        <div class="grid/row">
        @count ($rooms as $room)
            @include('lobby.room')
            
            @if(Counter::even() && ! Counter::last())
                </div>
                <div class="grid/row">
            @endif
        @endcount
        </div>
    </main>
@stop
