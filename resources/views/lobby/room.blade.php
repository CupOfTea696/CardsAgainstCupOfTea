<div class="lobby/room grid/sm/one/half" id="{{ $room->slug }}">
    <header>
        <h5 class="pull-right">{{ $room->status }}</h5>
        <h4 class="lobby/room/name">{{ $room->name }}</h4>
    </header>
    <main>
        <ul class="lobby/room/meta">
            <li>
                <strong>{{ ucf_trans_choice('players', 0) }}:</strong> {{ $room->players }}/{{ $room->capacity }}
            </li>
            <li>
                <strong>{{ ucf_trans_choice('spectators', 0) }}:</strong> {{ $room->spectators }}/{{ $room->spec_capacity }}
            </li>
        </ul> 
        <div class="lobby/room/actions pull-right">
            <button class="lobby/room/actions/join">Join</button>
            <button class="lobby/room/actions/spectate">Spectate</button>
        </div>
    </main>
</div>