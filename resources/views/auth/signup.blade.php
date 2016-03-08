@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>{{ $app_name }}</h1>
    </header>
    <main class="text-center">
        <form action="{{ route('signUp.do') }}" method="post" data-pjax="#container">
            {{ csrf_field() }}
            
            <div class="form-group col-md-12">
                <h2>{{ ucf_trans('auth.signup') }}!</h2>
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="text" class="form-control" name="username" placeholder="{{ uc_trans('auth.user') }}" required{!! (isset($user->username) ? ' value="' . $user->username . '"' : '') !!}>
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="password" class="form-control" name="password" placeholder="{{ uc_trans('auth.pass') }}" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="email" class="form-control" name="email" placeholder="{{ uc_trans('auth.@') }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <button type="submit" class="btn btn-primary btn-block">{{ uc_trans('auth.signup') }}</button>
            </div>
        </form>
    </main>
@stop