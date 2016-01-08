@extends('layouts.main')

@section('page')
    <header class="text-center">
        <h1>Cards Against CupOfTea</h1>
    </header>
    <main class="text-center">
        <form action="{{ route('login.do') }}">
            <div class="form-group col-md-12">
                <h2>{{ trans('auth.login') }}</h2>
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group col-md-4 col-md-offset-4">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            
            <div class="form-group col-md-4 col-md-offset-4">
                <button type="submit" class="btn btn-primary btn-block">{{ trans('auth.login') }}</button>
            </div>
        </form>
    </main>
@stop