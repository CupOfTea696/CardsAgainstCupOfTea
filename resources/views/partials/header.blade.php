<header class="nav/container">
    <nav class="nav +z-depth-1">
        <div class="pull-left">
            <div class="nav/item --main">
                {{ $app_name }}
                <a href="{{ Auth::check() ? route('home') : route('lobby') }}" class="+clickable"></a>
            </div>
        </div>
        <div class="pull-right">
            @if (Auth::check())
                <div class="nav/item">
                    {{ trans('auth.account') }}
                    <a href="{{ route('account') }}" class="+clickable"></a>
                </div>
                <div class="nav/item">
                    {{ trans('auth.logout') }}
                    <a href="{{ route('logout') }}" class="+clickable" data-pjax="#container"></a>
                </div>
            @else
                <div class="nav/item">
                    {{ trans('auth.login') }}
                    @if (current_route('name') == 'home')
                        <a href="{{ route('login') }}" class="+clickable"></a>
                    @else
                        <a data-action="login" class="+clickable"></a>
                    @endif
                </div>
            @endif
        </div>
    </nav>
</header>
