(function ($) {

    $.fn.wsSearch = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.wsSearch');
            return false;
        }
    };

    var defaults = {
        typeOfSearch: undefined,
        enableFilter: true,
        filterParams: 'filter',
        absoluteUrl: undefined,
        filterContentUrls: []
    };

    var eventHandlers = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $search = $(this);
                var settings = $.extend({}, defaults, options || {});
                var portalNames = Object.keys(settings.filterContentUrls);

                $search.data('wsSearch', {
                    settings: settings,
                    portals: portalNames,
                });
                initEventHandler($search, 'filter', 'change', 'input.form-check-input', function (event) {

                })

            });
        },
        applyFilter: function ($search, portal) {
            console.log(portal)
        },
        data: function () {
            return this.data('wsSearch');
        },
    };
    var initEventHandler = function ($search, type, event, selector, callback) {
        var id = $search.attr('id');
        var prevHandler = eventHandlers[id];
        if (prevHandler !== undefined && prevHandler[type] !== undefined) {
            var data = prevHandler[type];
            $(document).off(data.event, data.selector);
        }
        if (prevHandler === undefined) {
            eventHandlers[id] = {};
        }
        console.log(event + selector + callback);
        $(document).on(event, selector, callback);
        eventHandlers[id][type] = {event: event, selector: selector};
    }
})(jQuery);