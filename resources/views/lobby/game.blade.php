<div class="lobby/game grid/sm/one/half" id="{{ $game->slug }}">
    <header class="lobby/game/header">
        <h5 class="lobby/game/status pull-right">{{ $game->status }}</h5>
        <h4 class="lobby/game/name">{{ $game->name }}</h4>
    </header>
    <main>
        <div class="lobby/game/actions">
            <button class="btn btn-default btn-block --ripple --dark">Spectate</button>
            <a href="{{ route('game.show', [$game]) }}" class="btn btn-primary btn-block --big --ripple">Join</a>
        </div>
        
        <ul class="lobby/game/meta">
            <li>
                <strong>{{ ucf_trans_choice('lobby.players', 0) }}:</strong> {{ $game->players }}/{{ $game->capacity }}
            </li>
            <li>
                <strong>{{ ucf_trans_choice('lobby.spectators', 0) }}:</strong> {{ $game->spectators }}/{{ $game->spec_capacity }}
            </li>
        </ul> 
        <div class="lobby/game/details hide">
            <strong>
                <a data-action="details">Show details</a>
            </strong> 
        </div>
    </main>
</div>