@extends('layouts.main')

@section('page')
    <header>
        @include('partials.alerts')
        <h3>{{ trans('auth.account.welcome', ['name' => $user->username]) }}</h3>
    </header>
    <main>
        <section class="account/edit">
            <header>
                <h4>{{ trans('auth.edit.heading') }}</h4>
            </header>
            <main class="grid --no-grow">
                <form action="{{ route('account.update') }}" method="post" class="form --horizontal grid/sm/one/half">
                    {{ csrf_field() }}
                    
                    <div class="form/group grid">
                        <label for="account/password" class="grid/sm/one/fourth">{{ uc_trans('auth.pass.change') }}</label>
                        <div class="grid/sm/three/fourths">
                            <input type="password" class="form/control" name="password" id="account/password" placeholder="{{ uc_trans('auth.pass') }}">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form/group grid">
                        <label for="account/email" class="grid/sm/one/fourth">{{ uc_trans('auth.@.change') }}</label>
                        <div class="grid/sm/three/fourths">
                            <input type="email" class="form/control" name="email" id="account/email" placeholder="{{ uc_trans('auth.@') }}" value="{{ $user->email }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form/group grid --no-grow">
                        <div class="grid/sm/offset/one/fourth grid/sm/three/fourths">
                            <button type="submit" class="btn btn-primary btn-block">{{ uc_trans('auth.save') }}</button>
                        </div>
                    </div>
                </form>
            </main>
        </section>
        <section class="account/delete">
            <header>
                <h4>{{ trans('auth.delete.heading') }}</h4>
            </header>
            <main class="grid --no-grow">
                <div class="grid/sm/one/half">
                    <p class="lead">
                        {{ trans('auth.delete.warn') }}
                    </p>
                    <div class="grid --no-grow">
                        <div class="grid/sm/three/fourths">
                            <button class="btn btn-danger btn-block">{{ uc_trans('auth.delete') }}</button>
                        </div>
                    </div>
                </div>
            </main>
        </section>
    </main>
@stop
