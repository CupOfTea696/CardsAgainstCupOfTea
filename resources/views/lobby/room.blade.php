<div class="lobby/room grid/sm/one/half" id="{{ $room->slug }}">
    <header class="lobby/room/header">
        <h5 class="lobby/room/status pull-right">{{ $room->status }}</h5>
        <h4 class="lobby/room/name">{{ $room->name }}</h4>
    </header>
    <main>
        <div class="lobby/room/actions">
            <button class="btn btn-default btn-block --ripple --dark">Spectate</button>
            <button class="btn btn-primary btn-block --big --ripple">Join</button>
        </div>
        
        <ul class="lobby/room/meta">
            <li>
                <strong>{{ ucf_trans_choice('players', 0) }}:</strong> {{ $room->players }}/{{ $room->capacity }}
            </li>
            <li>
                <strong>{{ ucf_trans_choice('spectators', 0) }}:</strong> {{ $room->spectators }}/{{ $room->spec_capacity }}
            </li>
        </ul> 
        <div class="lobby/room/details hide">
            <strong>
                <a data-action="details">Show details</a>
            </strong> 
        </div>
    </main>
</div>