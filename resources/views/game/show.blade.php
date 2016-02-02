@extends('layouts.main')

@inject('logic', 'App\Game\Logic')

@section('page')
    {{ $game->name }}
    
    @include('game.settings')
@stop