@extends('layouts.main')

@inject('logic', 'CAT\Game\Logic')

@section('page')
    <header>
        <h2>{{ trans('game.password.heading') }}</h2>
    </header>
    <main>
        <form action="{{ route('game.auth') }}" method="post" class="form --horizontal">
            {{ csrf_field() }}
            
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
        </form>
    </main>
@stop
