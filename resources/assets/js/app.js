(function(window, $) {
    var document = window.document;
    
    $(document).on('click', 'a[href^="{{ url('') }}"]', function(e) {
        var container;
        
        if (! container = $(this).data('pjax')) {
            container = '#page';
        }
        
        $.pjax.click(e, {container: container});
    });
    
    $(document).on('submit', 'form', function(e) {
        var container;
        
        if (! container = $(this).data('pjax')) {
            container = '#page';
        }
        
        $.pjax.submit(e, container);
    });
})(window, $);