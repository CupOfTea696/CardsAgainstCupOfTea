(function(window, app, $) {
    var document = window.document;
    
    if ($.support.pjax) {
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
        
        $(document).on('pjax:success', function(e) {
            $(e.target).removeClass('+loading');
        });
        
        $(document).on('pjax:error', function(event, jqXHR, textStatus, error, options) {
            window.xhr = jqXHR;
            console.log([event, jqXHR, textStatus, error, options]);
            if (! (options.type == 'GET' && textStatus !== 'abort') && jqXHR.xhr.responseURL === options.requestUrl)  {
                document.write(jqXHR.responseText);
                document.title = jqXHR.status + ' ' + error;
            }
        });
    }
})(window, app, jQuery);
