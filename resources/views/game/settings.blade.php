<form class="game/settings form --horizontal">
    <fieldset>
        <legend>{{ ucf_trans('game.settings') }}</legend>
        <div class="form/group grid --no-grow --from-start">
            <label for="game/score_limit" class="grid/sm/one/fourth">{{ uc_trans('game.props.score_limit') }}</label>
            <div class="grid/sm/one/half">
                <input type="number" class="form/control" name="capacity" id="game/score_limit"
                       min="{{ config('game.score_limit.min') }}" max="{{ config('game.score_limit.max') }}" value="{{ config('game.score_limit.default') }}">
            </div>
        </div>
        <div class="form/group grid --no-grow --from-start">
            <label for="game/capacity" class="grid/sm/one/fourth">{{ uc_trans('game.props.capacity') }}</label>
            <div class="grid/sm/one/half">
                <input type="number" class="form/control" name="capacity" id="game/capacity"
                       min="{{ config('game.capacity.min') }}" max="{{ config('game.capacity.max') }}" value="{{ $game->capacity }}">
            </div>
        </div>
        <div class="form/group grid --no-grow --from-start">
            <label for="game/spec_capacity" class="grid/sm/one/fourth">{{ uc_trans('game.props.spec_capacity') }}</label>
            <div class="grid/sm/one/half">
                <input type="number" class="form/control" name="spec_capacity" id="game/spec_capacity"
                       min="{{ config('game.spec_capacity.min') }}" max="{{ config('game.spec_capacity.max') }}" value="{{ $game->spec_capacity }}">
            </div>
        </div>
        @if (Auth::check())
            <div class="form/group grid --no-grow --from-start">
                <label for="game/private" class="grid/sm/one/fourth">{{ uc_trans('game.props.private') }}</label>
                <div class="grid/sm/one/half">
                    <input type="checkbox" class="form/control form/checkbox" name="private" id="game/private"{{ $game->private ? ' checked' : '' }}>
                    <label for="game/private"></label>
                    <span class="help-block">
                        <strong>{{ trans('game.props.private.help', ['site_name' => $app_name]) }}</strong>
                    </span>
                </div>
            </div>
            <div class="form/group grid --no-grow --from-start">
                <label for="game/password" class="grid/sm/one/fourth">{{ uc_trans('game.props.pass.label') }}</label>
                <div class="grid/sm/one/half">
                    <input type="password" class="form/control" name="password" id="game/password" placeholder="{{ $game->password }}">
                </div>
            </div>
        @endif
    </fieldset>
    <fieldset>
        <legend>{{ uc_trans_choice('game.decks', 0) }}</legend>
        <div class="form/group grid --no-grow --from-start">
            <label class="grid/sm/one/fourth">{{ uc_trans('game.deck.main') }}</label>
            <div class="grid/sm/one/half">
                @foreach ($logic->locales as $locale)
                    <input type="checkbox" class="form/control form/checkbox" name="decks" id="game/decks/{{ $locale }}">
                    <label for="game/decks/{{ $locale }}">{{ uc_trans('game.locales.' . $locale) }}</label>
                @endforeach
            </div>
        </div>
        <div class="form/group grid --no-grow --from-start">
            <label class="grid/sm/one/fourth">{{ uc_trans('game.deck.official') }}</label>
            <div class="grid/sm/one/half">
                @foreach ($logic->expansions as $deck => $file)
                    <input type="checkbox" class="form/control form/checkbox" name="decks" id="game/decks/{{ $deck }}">
                    <label for="game/decks/{{ $deck }}">{{ str_replace('', '', $deck) }}</label>
                @endforeach
            </div>
        </div>
    </fieldset>
</form>