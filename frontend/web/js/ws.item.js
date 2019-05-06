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

    var events = {
        ajaxBeforeSend: 'ajaxBeforeSend',
        ajaxComplete: 'ajaxComplete',
        afterInit: 'afterInit'
    };
    var defaultParams = {
        variation_mapping: [],
        variation_options: [],
        sellers: [],
        conditions: [],
        images: [],
    };
    var defaultOptions = {
        ajaxUrl: undefined,
        ajaxMethod: 'POST',
        queryParams: []
    };
    var currentVariations = [];

    var methods = {
        init: function (params, options) {
            return this.each(function () {
                var $item = $(this);
                if ($item.data('wsItem')) {
                    return;
                }
                params = $.extend({}, defaultParams, params || {});
                options = $.extend({}, defaultOptions, options || {});

                $.each(params.variation_options, function (index, variationOption) {
                    checkValidVariation($item, variationOption);
                    watchVariationOptions($item, variationOption);
                });
                setUpDefaultOptions($item);
                $item.data('wsItem', {
                    options: options,
                    params: params,
                    ajaxCalling: false
                });
                $item.trigger($.Event(events.afterInit));
            });
        },
        changeVariation: function (variationOption, selectedValue) {
            var $item = $(this);
            var data = $item.data('wsItem');
            // update Image here;
            var deferredArrays = deferredArray();
            var params = data.params;
            currentVariations = currentVariations.filter(c => c.name !== variationOption.name);
            currentVariations.push({name:  variationOption.name, value: selectedValue});
            console.log(currentVariations);
            const activeVariation = findVariation(params.variation_mapping, currentVariations);
            if (activeVariation !== undefined) {
                $.when.apply(this, deferredArrays).always(function () {
                    var queryParams = data.options.queryParams;
                    queryParams.sku = activeVariation.variation_sku;
                    $.ajax({
                        url: data.options.ajaxUrl,
                        type: 'POST',
                        data: queryParams,
                        dataType: 'json',
                        complete: function (jqXHR, textStatus) {
                            $item.trigger(events.ajaxComplete, [jqXHR, textStatus]);
                        },
                        beforeSend: function (jqXHR, settings) {
                            $item.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
                        },
                        success: function (content) {
                            updateContent($item, content, data.ajaxed)
                        },
                        error: function () {
                            data.ajaxed = false;
                        }
                    });
                });
            }
        },
        data: function () {
            return this.data('wsItem');
        },
    };
    var deferredArray = function () {
        var array = [];
        array.add = function (callback) {
            this.push(new $.Deferred(callback));
        };
        return array;
    };
    var setUpDefaultOptions = function (item) {

    };

    var updateContent = function ($item, content, isCalling) {
        console.log(content);
    };
    changeImage = function (imgs) {

    };
    var watchVariationOptions = function ($item, variationOption) {
        var $input = findInput($item, variationOption);
        var name = variationOption.name;
        var type = $input.attr('type');
        if (type === 'spanList') {
            $input.on('click.wsItem', function (e) {
                methods.changeVariation.call($item, variationOption, $(this).data('value'));
            });
        } else {
            $input.on('change.wsItem', function (e) {
                methods.changeVariation.call($item, variationOption, $(this).val());
            });
        }
    };
    var checkValidVariation = function ($item, variationOption) {

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