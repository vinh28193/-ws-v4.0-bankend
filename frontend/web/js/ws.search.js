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
        absoluteUrl: undefined
    };

    var queryParams = {};

    var Response = {
        success: false,
        message: 'failed',
        data: []
    };
    var methods = {
        init: function (options) {
            return this.each(function () {
                var $search = $(this);
                if ($search.data('wsSearch')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                if (settings.absoluteUrl === undefined) {
                    settings.absoluteUrl = $search.attr('data-action');
                }
                $.each(yii.getQueryParams(settings.absoluteUrl), function (name, value) {
                    queryParams[name] = value;
                });


            });
        },
        queryParams: function () {
            return queryParams;
        },
    }
})(jQuery);