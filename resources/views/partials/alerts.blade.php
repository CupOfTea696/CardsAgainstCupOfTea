@forelse (session('alerts', []) as $alert)
    @include('partials.alert')
@empty
    @if ($alert = session('alert'))
        @include('partials.alert')
    @endif
@endforelse