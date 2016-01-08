<header>
    <nav class="navbar">
        <div class="pull-left">
            <a href="">Logo</a>
        </div>
        <div class="pull-right">
            @if (current_route('name') == 'home')
                <a href="#login">{{ trans('auth.login') }}</a>
            @else
                <a href="{{ route('login') }}">{{ trans('auth.login') }}</a>
            @endif
        </div>
    </nav>
</header>