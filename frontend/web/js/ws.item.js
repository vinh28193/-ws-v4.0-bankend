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
        options_group: [],
        sellers: [],
        conditions: [],
        images: [],
        current_variation: []
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $item = $(this);
                if ($item.data('wsItem')) {
                    return;
                }
                var setting = $.extend({}, options, defaults || {});
                $item.data('wsItem', setting);
            });
        },
        data: function () {
            return this.data('wsItem');
        },
    }
})(jQuery);