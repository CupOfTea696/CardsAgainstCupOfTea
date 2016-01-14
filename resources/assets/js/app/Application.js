var app = {};

(function(window, $, app) {
    var location = window.location;
    
    app.doc = window.document;
    app.$ = $;
    app.location = location;
    app.baseUrl = location.protocol + '//' + location.host + (location.port ? ':' + location.port : '');
})(window, jQuery, app);