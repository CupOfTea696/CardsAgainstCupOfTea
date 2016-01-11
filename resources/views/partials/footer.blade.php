<footer>
    <div class="container">
        <div class="pull-left">
            &copy; {{ date('Y') }} by CupOfTea
        </div>
        @if (App::environment('local', 'development'))
            <div class="pull-right">
                {{ App::environment() }}
                //
                {{ time() }}
            </div>
        @endif
    </div>
</footer>
