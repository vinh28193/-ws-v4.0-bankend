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

    var paymentMethod = undefined;

    var events = {
        ajaxBeforeSend: 'ajaxBeforeSend',
        ajaxComplete: 'ajaxComplete',
        afterInit: 'afterInit',
        afterAddToCart: 'afterAddToCart',
        afterBuyNow: 'afterBuyNow'
    };
    var defaultParams = {
        id: undefined,
        sku: undefined,
        seller: undefined,
        condition: undefined,
        type: undefined,
        variation_mapping: [],
        variation_options: [],
        sellers: [],
        conditions: [],
        images: [],
    };
    var defaultOptions = {
        ajaxUrl: undefined,
        ajaxMethod: 'POST',
        paymentUrl: undefined,
        queryParams: [],
        priceCssSelection: 'price',
        slideCssSelection: 'detail-slider'
    };
    var priceUpdateResponse = {
        fees: [],
        queryParams: [],
        sellPrice: 0,
        startPrice: 0,
        salePercent: 0
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

                $item.data('wsItem', {
                    options: options,
                    params: params,
                    ajaxCalling: false
                });
                var images = params.images;
                if (images.length > 0) {
                    changeImage($item, images);
                }
                setUpDefaultOptions($item);
                ws.initEventHandler($item, 'addToCart', 'click.wsItem', 'button#addToCart', function (event) {
                    // console.log(this);
                    methods.addToCart.apply($item);
                    return false;
                });
                ws.initEventHandler($item, 'buyNow', 'click.wsItem', 'button.btn-buy', function (event) {
                    methods.buyNow.apply($item);
                    return false;
                });
                ws.initEventHandler($item, 'follow', 'click.wsItem', 'button#follow', function (event) {
                    methods.follow.apply($item);
                    return false;
                });
                ws.initEventHandler($item, 'quote', 'click.wsItem', 'button#quote', function (event) {
                    methods.quote.apply($item);
                    return false;
                });
                $item.trigger($.Event(events.afterInit));
            });
        },
        changeVariation: function (variationOption, selectedValue) {
            var $item = $(this);
            var data = $item.data('wsItem');
            const value = variationOption.values[selectedValue];
            const name = variationOption.name;
            if (value === undefined) {
                return;
            }
            if (variationOption.images_mapping.length > 0) {
                const imgs = variationOption.images_mapping.filter(i => i.value === value);
                if (imgs.length > 0) {
                    changeImage($item, imgs[0].images);
                }
            }
            var deferredArrays = deferredArray();
            var params = data.params;
            currentVariations = currentVariations.filter(c => c.name !== name);
            currentVariations.push({name: name, value: value});
            const activeVariation = findVariation(params.variation_mapping, currentVariations);
            if (activeVariation !== undefined) {
                $.when.apply(this, deferredArrays).always(function () {
                    var queryParams = data.options.queryParams;
                    queryParams.sku = activeVariation.variation_sku;
                    ws.ajax(data.options.ajaxUrl, {
                        type: 'POST',
                        data: queryParams,
                        dataType: 'json',
                        complete: function (jqXHR, textStatus) {
                            $item.trigger(events.ajaxComplete, [jqXHR, textStatus]);
                        },
                        beforeSend: function (jqXHR, settings) {
                            $item.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
                        },
                        success: function (response) {
                            if (response.success) {
                                data.ajaxed = true;
                                var content = $.extend({}, priceUpdateResponse, response.content || {});
                                updatePrice($item, content, data.ajaxed)
                            }
                        },
                        error: function () {
                            data.ajaxed = false;
                        }
                    }, true);
                });
            }
        },
        addToCart: function () {

        },
        buyNow: function () {
            var $item = $(this);
            paymentItem($item, 'buyNow', paymentMethod);
        },
        follow: function () {

        },
        quote: function () {

        },
        destroy: function () {
            return this.each(function () {
                $(this).off('.wsItem');
                $(this).removeData('wsItem');
            });
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
    var setUpDefaultOptions = function ($item) {
        var data = $item.data('wsItem');
        if (data.params.variation_mapping.length === 0) {
            return;
        }
        var activeVariation = [];
        var sku = data.options.queryParams.sku;
        if (sku !== undefined) {
            activeVariation = data.params.variation_mapping.filter(m => m.variation_sku === sku);
        }
        if (activeVariation.length === 0) {
            activeVariation = data.params.variation_mapping[0];
        }
        var images = data.params.images;
        $.each(activeVariation.options_group, function (index, group) {
            for (var i = 0; i < data.params.variation_options.length; i++) {
                var variation_options = data.params.variation_options[i];
                if (group.name === variation_options.name) {
                    var values = variation_options.values;
                    for (var j = 0; j < values.length; j++) {
                        if (values[j] === group.value) {
                            var $input = findInput($item, variation_options);
                            var type = $input.attr('type');
                            if (type === 'spanList' && $input.length > 0 && typeof $input[j] !== 'undefined') {
                                console.log($input[j]);
                            } else if (type === 'dropDown' && $input.length > 0) {
                                console.log($input[0]);
                            }
                            if (variation_options.images_mapping.length > 0) {
                                const imgs = variation_options.images_mapping.filter(i => i.value === values[j]);
                                if (imgs.length > 0) {
                                    images = imgs[0].images;
                                }
                            }
                        }
                    }
                }
            }
        });
        changeImage($item, images);

    };
    var updateQuantity = function ($item, available, sold) {

    };
    var activeVariationMaping = function ($item, mapping) {

    };
    var updatePrice = function ($item, content, isCalling) {
        var data = $item.data('wsItem');
        var selection = 'div.' + data.options.priceCssSelection;
        $(selection).find('strong.text-orange').html(content.sellPrice);
        if (content.queryParams.sku !== undefined) {
            data.params.sku = content.queryParams.sku;
            $item.data('wsItem', data);
        }
        console.log(content);
    };
    var tester = function ($item) {
        var data = $item.data('wsItem');
        console.log(data);
    };
    var changeImage = function ($item, images) {
        var data = $item.data('wsItem');
        var selection = 'div.' + data.options.slideCssSelection;
        var html = '<div class="detail-slider">' +
            '<i class="fas fa-chevron-up slider-prev"></i>' +
            '<i class="fas fa-chevron-down slider-next"></i>' +
            '        <div id="detail-slider" class="slick-slider">';
        $.each(images, function (index, value) {
            if (index == 0)
                html += '<div class="item active">';
            else {
                html += '<div class="item">';
            }
            html += '<a href="#" data-image="' + value.main + '" data-zoom-image="' + value.main + '"> ' +
                '<img src="' + value.main + '" width="100"/>' +
                '</a>';
            html += '</div>'
        });
        html += '</div></div>';
        html += '<div class="big-img">' +
            '<img id="detail-big-img" class="detail-big-img" src="' + images[0].main + '" data-zoom-image="' + images[0].main + '" width="400"/>' +
            '</div>';
        $(selection).html(html);
    };
    var watchVariationOptions = function ($item, variationOption) {
        var $input = findInput($item, variationOption);
        var name = variationOption.name;
        var type = $input.attr('type');
        console.log($input);
        if (type === 'spanList') {
            $input.on('click.wsItem', function (e) {
                methods.changeVariation.call($item, variationOption, $(this).data('index'));
            });
        } else {
            $input.on('change.wsItem', function (e) {
                methods.changeVariation.call($item, variationOption, Number($(this).val()));
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
        var id = variationOption.id;
        var $dataRef = '[data-ref=' + variationOption.id + ']';
        var selection = $dataRef + ' #' + id.toLowerCase();
        var $input = $item.find(selection);
        if ($input.length && $input[0].tagName.toLowerCase() === 'ul') {
            return $input.find('span');
        } else {
            return $input;
        }
    };

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
    var paymentItem = function ($item, type, paymentMethod) {
        var data = $item.data('wsItem');
        var params = data.params;
        var data = {
            source: params.type,
            seller: params.seller,
            sku: params.id,
            image: params.images[0].main
        };
        if (params.sku !== null && params.sku !== data.id) {
            data.parentSku = data.sku;
            data.sku = params.sku;
        }
        console.log(data);
        var $ajaxOptions = {
            type: 'POST',
            dataType: 'json',
            data: data
        }
    }
})(jQuery);