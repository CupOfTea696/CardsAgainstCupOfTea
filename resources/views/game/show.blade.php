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
        @foreach($players as $player)
            <div class="game/player">
                <img src="http://gravatar.com/avatar/{{ md5($player->email) }}?s=64&d=identicon" alt="{{ $player->username }}" class="game/player/avatar">
                <div class="game/player/username">
                    {{ $player->username }}
                </div>
                <div class="game/player/score">
                    0 {{ uc_trans_choice('game.awesome_points', 0) }}
                </div>
            </div>
        @endforeach
        @foreach($spectators as $spectator)
            <div class="game/spectator">
                <img src="http://gravatar.com/avatar/{{ md5($spectator->email) }}?s=64&d=identicon" alt="{{ possessive($spectator->username) }} Avatar" class="game/spectator/avatar">
                <div class="game/spectator/username">
                    {{ $spectator->username }}
                </div>
                <div class="game/spectator/status">
                    {{ uc_trans('game.status.spectating') }}
                </div>
            </div>
        @endforeach
    </aside>
@stop
