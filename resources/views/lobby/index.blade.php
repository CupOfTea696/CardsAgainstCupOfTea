@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>{{ trans('lobby.welcome.heading') }}</h1>
        <p class="lead">
            {{ trans('lobby.welcome.message') }}
        </p>
    </header>
    <main>
        @foreach ($rooms as $room)
            @include('lobby.room')
        @endforeach
    </main>
@stop
