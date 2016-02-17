@extends('layouts.main')

@inject('logic', 'CAT\Game\Logic')

@section('page')
    <header>
        <h2>{{ trans('game.create.heading') }}</h2>
    </header>
    <main>
        <form action="{{ route('game.store') }}" method="post" class="form --horizontal">
            {{ csrf_field() }}
            
            <div class="form/group grid --no-grow --from-start">
                <label for="game/name" class="grid/sm/one/fourth">{{ uc_trans('game.props.name.label') }}</label>
                <div class="grid/sm/one/half">
                    <input type="text" class="form/control" name="name" id="game/name" placeholder="{{ $logic->createNameFromUsername() ?: uc_trans('game.props.name') }}">
                    @if ($errors->has('slug'))
                        <span class="help-block">
                            <strong>{{ $errors->first('slug') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form/group grid --no-grow --from-start">
                <label for="game/capacity" class="grid/sm/one/fourth">{{ uc_trans('game.props.capacity') }}</label>
                <div class="grid/sm/one/half">
                    <input type="number" class="form/control" name="capacity" id="game/capacity"
                           min="{{ config('game.capacity.min') }}" max="{{ config('game.capacity.max') }}" value="{{ config('game.capacity.default') }}">
                    @if ($errors->has('capacity'))
                        <span class="help-block">
                            <strong>{{ $errors->first('capacity') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form/group grid --no-grow --from-start">
                <label for="game/spec_capacity" class="grid/sm/one/fourth">{{ uc_trans('game.props.spec_capacity') }}</label>
                <div class="grid/sm/one/half">
                    <input type="number" class="form/control" name="spec_capacity" id="game/spec_capacity"
                           min="{{ config('game.spec_capacity.min') }}" max="{{ config('game.spec_capacity.max') }}" value="{{ config('game.spec_capacity.default') }}">
                    @if ($errors->has('spec_capacity'))
                        <span class="help-block">
                            <strong>{{ $errors->first('spec_capacity') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            @if (Auth::check())
                <div class="form/group grid --no-grow --from-start">
                    <label for="game/private" class="grid/sm/one/fourth">{{ uc_trans('game.props.private') }}</label>
                    <div class="grid/sm/one/half">
                        <input type="checkbox" class="form/control form/checkbox" name="private" id="game/private">
                        <label for="game/private"></label>
                        <span class="help-block">
                            <strong>{{ trans('game.props.private.help', ['site_name' => $app_name]) }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form/group grid --no-grow --from-start">
                    <label for="game/password" class="grid/sm/one/fourth">{{ uc_trans('game.props.pass.label') }}</label>
                    <div class="grid/sm/one/half">
                        <input type="password" class="form/control" name="password" id="game/password" placeholder="{{ uc_trans('game.props.pass') }}">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>

                        @endif
                    </div>
                </div>
            @endif
            
            <div class="form/group grid --no-grow --from-start">
                <div class="grid/sm/offset/one/fourth grid/sm/one/fourth">
                    <button type="submit" class="btn btn-primary btn-block">{{ uc_trans('game.create') }}</button>
                </div>
            </div>
        </form>
    </main>
@stop
