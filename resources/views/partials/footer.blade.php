<footer>
    <div class="container">
        @if (App::environment('local', 'development'))
            <div class="pull-left">
                {{ App::environment() }}
                //
                {{ time() }}
            </div>
        @endif
        <div class="pull-right">
            &copy; {{ date('Y') }} by <a href="http://tiny.cc/cot696" target="_blank">CupOfTea</a>
        </div>
    </div>
</footer>
