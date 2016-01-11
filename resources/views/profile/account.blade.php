@extends('layouts.main')

@section('page')
    <header>
        <h1>{{ trans('auth.account.welcome', ['name' => $user->username]) }}</h1>
    </header>
    <main>
        <form action="" method="post">
            {{ csrf_field() }}
        </form>
    </main>
@stop
