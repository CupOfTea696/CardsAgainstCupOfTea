<div class="lobby/game grid/sm/one/half" id="{{ $game->slug }}">
    <header class="lobby/game/header">
        <h5 class="lobby/game/status pull-right">{{ $game->status }}</h5>
        <h4 class="lobby/game/name">{{ $game->name }}</h4>
    </header>
    <main>
        <div class="lobby/game/actions">
            <form action="{{ route('game.show', [$game]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="join_as" value="spectator">
                
                <button class="btn btn-default btn-block --ripple --dark" type="submit">Spectate</button>
                <a href="{{ route('game.show', [$game]) }}" class="btn btn-primary btn-block --big --ripple">Join</a>
            </form>
        </div>
        
        <ul class="lobby/game/meta">
            <li>
                <strong>{{ ucf_trans_choice('lobby.players', 0) }}:</strong> {{ $game->player_count }}/{{ $game->capacity }}
            </li>
            <li>
                <strong>{{ ucf_trans_choice('lobby.spectators', 0) }}:</strong> {{ $game->spectator_count }}/{{ $game->spec_capacity }}
            </li>
        </ul> 
        <div class="lobby/game/details hide">
            <strong>
                <a data-action="details">Show details</a>
            </strong> 
        </div>
    </main>
</div>