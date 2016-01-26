(function(window, $, app) {
    var start = function(container) {
        $(container).addClass('+loadable +loading');
    };
    
    var complete = function(container, route) {
        var $container = $(container);
        
        $container.removeClass('+loading').on(app.transitionEvent(), function() {
           var $this = $(this); 
            
            $this.off(app.transitionEvent());
            $this.removeClass('+loadable');
        });
        
        $('.nav\\/item').removeClass('--active');
        
        if (route !== 'home') {
            $('.nav\\/item').filter('[data-route="' + route + '"]').addClass('--active');
        }
    };
    
    app.Navigation = {
        start: start,
        complete: complete
    };
})(window, jQuery, app);
