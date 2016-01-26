<header class="nav/container">
    <nav class="nav +z-depth-1">
        <div class="pull-left">
            <div class="nav/item --main" data-route="home">
                {{ $app_name }}
                <a href="{{ Auth::check() ? route('home') : route('lobby') }}" class="+clickable"></a>
            </div>
            @if (Auth::check())
                <div class="nav/item{{ is_current_route('room.create') ? ' --active' : '' }}" data-route="room.create">
                    {{ uc_trans('game.create') }}
                    <a href="{{ route('room.create') }}" class="+clickable"></a>
                </div>
            @endif
        </div>
        <div class="pull-right">
            @if (Auth::check())
                <div class="nav/item{{ is_current_route('account.edit') ? ' --active' : '' }}" data-route="account.edit">
                    {{ uc_trans('auth.account') }}
                    <a href="{{ route('account.edit') }}" class="+clickable"></a>
                </div>
                <div class="nav/item" data-route="logout">
                    {{ uc_trans('auth.logout') }}
                    <a href="{{ route('logout') }}" class="+clickable" data-pjax="#container"></a>
                </div>
            @else
                @if (current_route('name') != 'home')
                    <div class="nav/item{{ is_current_route('signUp') ? ' --active' : '' }}" data-route="signUp">
                        {{ uc_trans('auth.signup') }}
                        <a href="{{ route('signUp') }}" class="+clickable"></a>
                    </div>
                @endif
                <div class="nav/item{{ is_current_route('login') ? ' --active' : '' }}" data-route="login">
                    {{ uc_trans('auth.login') }}
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
