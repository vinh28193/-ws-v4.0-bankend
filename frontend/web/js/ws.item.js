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
                    checkValidVariation($item, variationOption);
                    watchVariationOptions($item, variationOption);
                });
                setUpDefaultOptions($item);
                $item.data('wsItem', settings);
            });
        },
        changeVariation: function (variationName, variationValue) {
            var $item = $(this);
            var data = $item.data('wsItem');
            var requireOptions = data.variation_options.length;
            currentVariations = currentVariations.filter(c => c.name !== variationName);
            currentVariations.push({name: variationName, value: variationValue});
            const activeVariation = findVariation(data.variation_mapping, currentVariations);
            console.log(activeVariation);
        },
        data: function () {
            return this.data('wsItem');
        },
    };
    var setUpDefaultOptions = function (item) {
        
    };
    var watchVariationOptions = function ($item, variationOption) {
        var $input = findInput($item, variationOption);
        var name = variationOption.name;
        var type = $input.attr('type');
        if (type === 'spanList') {
            $input.on('click.wsItem', function (e) {
                methods.changeVariation.call($item, name, $(this).data('value'));
            });
        } else {
            $input.on('change.wsItem', function (e) {
                methods.changeVariation.call($item, name, $(this).val());
            });
        }
    };
    var checkValidVariation = function ($item,variationOption) {
        
    };
    var findVariation = function (mapping, options) {
        return $.grep(mapping, function (i) {
            return JSON.stringify(i.options_group.sort(sortBy('name'))) == JSON.stringify(options.sort(sortBy('name')));
        })[0];
    };
    var findInput = function ($item, variationOption) {
        var name = variationOption.name;
        var $dataRef = '[data-ref=' + variationOption.name + ']';
        var selection = $dataRef + ' #' + name.toLowerCase();
        var $input = $item.find(selection);
        if ($input.length && $input[0].tagName.toLowerCase() === 'ul') {
            return $input.find('span');
        } else {
            return $input;
        }
    }
    var sortBy = function sortBy(key, reverse) {

        // Move smaller items towards the front
        // or back of the array depending on if
        // we want to sort the array in reverse
        // order or not.
        var moveSmaller = reverse ? 1 : -1;

        // Move larger items towards the front
        // or back of the array depending on if
        // we want to sort the array in reverse
        // order or not.
        var moveLarger = reverse ? -1 : 1;

        /**
         * @param  {*} a
         * @param  {*} b
         * @return {Number}
         */
        return function (a, b) {
            if (a[key] < b[key]) {
                return moveSmaller;
            }
            if (a[key] > b[key]) {
                return moveLarger;
            }
            return 0;
        };

    };
})(jQuery);