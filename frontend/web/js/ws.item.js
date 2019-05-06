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
    };

    var currentVariations = [];

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $item = $(this);
                if ($item.data('wsItem')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                $.each(settings.variation_options, function (index, variationOption) {
                    watchVariationOptions($item, variationOption);
                });
                $item.data('wsItem', settings);
            });
        },
        changeVariation:function(value) {

        },
        data: function () {
            return this.data('wsItem');
        },
    };

    var watchVariationOptions = function ($item, variationOption) {
        var $input = findInput($item, variationOption);
        console.log($input);
        var type = $input.attr('type');
        $input.on('change.wsItem', function (e) {
            console.log($(this).val());
        });
    };

    var findInput = function ($item, variationOption) {
        var $dataRef = '[data-ref=' + variationOption.name + ']';
        var selection = $dataRef + ' select,' + $dataRef + ' ul';
        var $input = $item.find(selection);
        if ($input.length && $input[0].tagName.toLowerCase() === 'ul') {
            return $input.find('input');
        } else {
            return $input;
        }
    }
})(jQuery);