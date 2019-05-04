(function ($) {

    $.fn.wsItem = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.wsItem');
            return false;
        }
    };

    var defaults = {
        variation_mapping: [],
        variation_options: [],
        sellers: [],
        conditions: [],
        images: [],
        current_variation: undefined
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $item = $(this);
                if ($item.data('wsItem')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                $.each(settings.variation_options, function (key,option) {
                    console.log(option);
                });
                $item.data('wsItem', settings);
            });
        },
        data: function () {
            return this.data('wsItem');
        },
    }

    var watchVariationOptions = function ($item, options) {

    }
})(jQuery);