(function(window, $, app) {
    var start = function(container) {
        $(container).addClass('+loadable +loading');
    };
    
    var complete = function(container, route) {
        $('form[data-readonly] :input:not(:checkbox):not(:radio)').prop('readonly', true);
        $('form[data-readonly] input:checkbox:not(.form\\/password_visibility_toggle), form[data-readonly] input:radio').prop('disabled', true);
        
        $('form[data-disabled] :input').prop('disabled', true);
        $('form[data-disabled] .form\\/password_visibility_toggle ~ label').css({display: 'none'});
        
        $('[data-resizable]').each(function() {
            var $this = $(this);
            var cfg = {
                handles: $this.data('resizable')
            };
            
            var types = ['min', 'max'];
            var dimensions = ['width', 'height'];
            var hasRelativeDimension = {
                width: false,
                height: false
            };
            
            for (var i = 0; i < types.length; i++) {
                for (var j = 0; j < dimensions.length; j++) {
                    var type = types[i];
                    var dimension = dimensions[j];
                    
                    var restriction = $this.data(type + '-' + dimension);
                    
                    if (restriction) {
                        if(restriction.match(/%$/)) {
                            restriction = parseFloat(restriction) / 100;
                            restriction = $this.css('position') == 'fixed' ? ($(window)[dimension]() * restriction) : ($this.parent()['outer' + dimension.ucfirst()]() * restriction);
                            
                            hasRelativeDimension[dimension] = true;
                        }
                        
                        cfg[type + dimension.ucfirst()] = restriction;
                    }
                }
            }
            
            var panel = $this.data('resizable-panel');
            var $related;
            
            if (panel) {
                $related = $('[data-resizable-panel="' + panel + '"]').not($this);
                
                cfg.start = function() {
                    $related.data('originalSize', {
                        width: $related.outerWidth(),
                        height: $related.outerHeight()
                    });
                };
                
                cfg.resize = function(e, ui) {
                    var wChange = ui.size.width - ui.originalSize.width;
                    var hChange = ui.size.height - ui.originalSize.height;
                    
                    if (wChange !== 0) {
                        $related.css({
                            width: $related.data('originalSize').width - wChange
                        });
                    }
                    
                    if (hChange !== 0) {
                        $related.css({
                            height: $related.data('originalSize').height - hChange
                        });
                    }
                };
            }
            
            cfg.stop = function() {
                for (var j = 0; j < dimensions.length; j++) {
                    var dimension = dimensions[j];
                    
                    console.log('Relative:', dimension, hasRelativeDimension[dimension]);
                    
                    if (hasRelativeDimension[dimension]) {
                        var containerDimension = $this.css('position') == 'fixed' ? $(window)[dimension]() : $this.parent()['outer' + dimension.ucfirst()]();
                        var relativeDimension = ($this['outer' + dimension.ucfirst()]() / containerDimension) * 100;
                        
                        console.log(containerDimension, relativeDimension);
                        
                        $this.css(dimension, relativeDimension + '%');
                        
                        if (panel) {
                            $related.css(dimension, (100 - relativeDimension) + '%');
                        }
                    }
                }
            };
            
            $this.resizable(cfg);
        });
        
        // If the container is set, this was a pjax request.
        if (typeof container !== 'undefined') {
            complete_pjax(container, route);
        }
    };
    
    var complete_pjax = function(container, route) {
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
    }
    
    app.Navigation = {
        start: start,
        complete: complete
    };
})(window, jQuery, app);
