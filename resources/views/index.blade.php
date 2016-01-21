@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>{{ $app_name }}</h1>
    </header>
    <main class="text-center">
        <form action="{{ route('join') }}" method="post" data-pjax="#container">
            {{ csrf_field() }}
            
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="text" class="form-control" name="username" placeholder="{{ uc_trans('auth.user') }}" required>
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <button type="submit" class="btn btn-primary btn-block">{{ uc_trans('game.play') }}</button>
            </div>
        </form>
        
        <form action="{{ route('loginOrSignUp') }}" method="post" data-pjax="#container">
            {{ csrf_field() }}
            
            <div class="form-group col-md-12">
                <p class="lead">
                    {{ trans('auth.signup.prompt') }}
                </p>
                <h2>{{ ucf_trans('auth.signup') }}!</h2>
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="text" class="form-control" name="username" placeholder="{{ uc_trans('auth.user') }}" required>
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="password" class="form-control" name="password" placeholder="{{ uc_trans('auth.pass') }}" required>
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="email" class="form-control" name="email" placeholder="{{ uc_trans('auth.@') }}">
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <button type="submit" class="btn btn-primary btn-block">{{ uc_trans('auth.signup') }}</button>
            </div>
        </form>
    </main>
@stop