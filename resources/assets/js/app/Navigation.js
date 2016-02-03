(function(window, $, app) {
    var start = function(container) {
        $(container).addClass('+loadable +loading');
    };
    
    var complete = function(container, route) {
        $('form[data-readonly] :input:not(:checkbox):not(:radio)').prop('readonly', true);
        $('form[data-readonly] input:checkbox:not(.form\\/password_visibility_toggle), form[data-readonly] input:radio').prop('disabled', true);
        
        $('form[data-disabled] :input').prop('disabled', true);
        $('form[data-disabled] .form\\/password_visibility_toggle ~ label').css({display: 'none'});
        
        // If the container isn't set, this was a direct request, not a pjax request.
        if (typeof container === 'undefined') {
            return;
        }
        
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
        
        $('body').removeClass().addClass('@' + route.replace('.', '/'));
    };
    
    app.Navigation = {
        start: start,
        complete: complete
    };
})(window, jQuery, app);
