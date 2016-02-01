@extends('layouts.main')

@inject('logic', 'App\Services\GameLogic')

@section('page')
    {{ $game->name }}
    
    @include('game.settings')
@stop