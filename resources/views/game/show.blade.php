@extends('layouts.main')

@inject('logic', 'CAT\Game\Logic')

@section('page')
    <main class="game/main" data-resizable="e" data-resizable-panel="game/main" data-min-width="50%" data-max-width="90%">
        <section class="game" data-resizable="s" data-resizable-panel="game" data-min-height="50%" data-max-height="80%">
            @include('game.settings')
            <header class="game/header">
                <h4>{{ $game->name }}</h4>
            </header>
            <main class="game/game">
                <p>Waiting for players...</p>
            </main>
        </section>
        <section class="game/chat chat" data-resizable-panel="game">
            <div class="chat/messages">
                
            </div>
            <div class="chat/input">
                <input type="text" name="chat" data-action="chat.publish" placeholder="{{ trans('chat.cta') }}" autofocus>
            </div>
        </section>
    </main>
    <aside class="game/users" data-resizable-panel="game/main">
        
    </aside>
@stop
