@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>{{ $app_name }}</h1>
    </header>
    <main class="text-center">
        <form action="{{ route('login.do') }}" method="post">
            {{ csrf_field() }}
            
            <div class="form-group col-md-12">
                <h2>{{ trans('auth.login') }}</h2>
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="text" class="form-control" name="username" placeholder="Username">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="password" class="form-control" name="password" placeholder="Password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <button type="submit" class="btn btn-primary btn-block">{{ trans('auth.login') }}</button>
            </div>
        </form>
    </main>
@stop