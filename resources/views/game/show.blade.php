@extends('layouts.main')

@inject('logic', 'App\Game\Logic')

@section('page')
    <section class="game">
        <div class="wrapper">
            @include('game.settings')
        </div>
        <header>
            <h4>{{ $game->name }}</h4>
        </header>
        <main class="game/game">
            <p>Waiting for players...</p>
        </main>
    </section>
    <section class="game/chat chat" data-resizable="n" data-max-height="50%">
        
    </section>
@stop
