(function(window, app, $) {
    var document = window.document;
    
    $(document).on('click', 'a[href^="' + app.baseUrl + '"]', function(e) {
        var container;
        
        if (! (container = $(this).data('pjax'))) {
            container = '#page';
        }
        
        $.pjax.click(e, {container: container, timeout: 3000});
    });
    
    $(document).on('submit', 'form', function(e) {
        var container;
        
        if (! (container = $(this).data('pjax'))) {
            container = '#page';
        }
        
        $.pjax.submit(e, container);
    });
    
    $(document).on('pjax:send', function(e) {
        $(e.target).addClass('+loadable +loading');
        
    });
    
    $(document).on('pjax:complete', function(e) {
        $(e.target).removeClass('+loading');
    });
})(window, app, jQuery);
