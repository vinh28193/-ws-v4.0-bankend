(function ($) {

    $.fn.wsCart = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.wsCart');
            return false;
        }
    };

    var defaults = {
        updateUrl: undefined,
        removeUrl: undefined,
    };

    var eventHandlers = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $cart = $(this);
                if ($cart.data('wsCart')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                $cart.data('wsCart', {settings: settings});
                
            });
        },
        add: function (params) {

        },
        update: function ($key, params) {

        },
        remove: function ($key) {

        },
        destroy: function () {
            return this.each(function () {
                $(this).off('.wsCart');
                $(this).removeData('wsCart');
            });
        },
        data: function () {
            return this.data('wsCart');
        },
    };
    var initEventHandler = function (key, type, event, selector, callback) {
        var prevHandler = eventHandlers[key];
        if (prevHandler !== undefined && prevHandler[type] !== undefined) {
            var data = prevHandler[type];
            $(document).off(data.event, data.selector);
        }
        if (prevHandler === undefined) {
            eventHandlers[key] = {};
        }
        $(document).on(event, selector, callback);
        eventHandlers[key][type] = {event: event, selector: selector};
    };
})(jQuery);