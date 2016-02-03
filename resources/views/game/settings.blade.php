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
                    <input type="checkbox" class="form/password_visibility_toggle" id="game/password/toggle" data-password="game/password">
                    <input type="password" class="form/control" name="password" id="game/password" placeholder="{{ uc_trans('game.props.pass') }}" value="{{ $game->password }}pass">
                    <label for="game/password/toggle"></label>
                </div>
            </div>
        @endif
    </fieldset>
    <fieldset>
        <legend>{{ uc_trans_choice('game.decks', 0) }}</legend>
        @foreach ($logic->sets as $set_id => $set)
            <div class="form/group grid --no-grow --from-start">
                <label class="grid/sm/one/fourth" data-set="game/decks/{{ $set_id }}">{{ uc_trans('game.sets.' . $set_id) }}</label>
                <div class="grid/sm/three/fourths">
                    @if ($set_id == 'main')
                        @foreach ($set->locales as $locale)
                            <span class="hint --top --rounded --bounce" data-hint="{{ uc_trans('game.locales.description') . uc_trans('game.locales.' . $locale) }}
                                                                                   [{{ $set->calls->$locale }} {{ trans_choice('game.calls', $set->calls->$locale) }},
                                                                                   {{ $set->responses->$locale }} {{ trans_choice('game.responses', $set->responses->$locale) }}]">
                                <input type="checkbox" class="form/control form/checkbox" name="decks" id="game/decks/{{ $locale }}" data-set="game/decks/{{ $set_id }}">
                                <label for="game/decks/{{ $locale }}">
                                    {{ uc_trans('game.locales.' . $locale) }}
                                </label>
                            </span>
                        @endforeach
                    @else
                        @foreach ($set as $id => $deck)
                            <span class="hint --top --rounded --bounce" data-hint="{{ strip_tags($deck->description) }}
                                                                                   [{{ $deck->calls }} {{ trans_choice('game.calls', $deck->calls) }},
                                                                                   {{ $deck->responses }} {{ trans_choice('game.responses', $deck->responses) }}]">
                                <input type="checkbox" class="form/control form/checkbox" name="decks" id="game/decks/{{ $id }}" data-set="game/decks/{{ $set_id }}">
                                <label for="game/decks/{{ $id }}">
                                    {{ $deck->name }}
                                </label>
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </fieldset>
</form>