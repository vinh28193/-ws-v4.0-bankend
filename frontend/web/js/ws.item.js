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
        quantity_sold: [],
        available_quantity: [],
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
        sellPrice: '',
        startPrice: '',
        salePercent: 0,
        contentPrice: '',
    };
    var varration_box = {
        image: '',
        title: ''
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
                defaultParams = params;
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
                    paymentItem($item, 'shopping');
                    return false;
                });
                ws.initEventHandler($item, 'buyNow', 'click.wsItem', 'button#buyNowBtn', function (event) {
                    paymentItem($item, 'buynow');
                    return false;
                });
                ws.initEventHandler($item, 'installment', 'click.wsItem', 'button#installmentBtn', function (event) {
                    paymentItem($item, 'installment');
                    return false;
                });
                ws.initEventHandler($item, 'follow', 'click.wsItem', 'a#followItem', function (event) {
                    methods.follow.apply($item);
                    return false;
                });
                ws.initEventHandler($item, 'quote', 'click.wsItem', 'button#quote', function (event) {
                    methods.quote.apply($item);
                    return false;
                });
                ws.initEventHandler($item, 'quantity', 'click.wsItem', 'button.btnQuantity', function (event) {
                    defaultParams = params;
                    methods.changeQuantity.apply(this);
                    return false;
                });
                ws.initEventHandler($item, 'quantityChange', 'change.wsItem', 'input#quantity', function (event) {
                    defaultParams = params;
                    methods.changeQuantity.apply(this);
                    return false;
                });
                ws.initEventHandler($item, 'mouseenter-box-select', 'mouseenter.wsItem', 'div.option-box span.box-select-item', function (event) {
                    defaultParams = params;
                    console.log($(this).children('img'));
                    methods.changeTempImage.call($item, $(this).children('img').attr('data-src'), $(this).children('img').attr('alt'), false);
                    return false;
                });
                ws.initEventHandler($item, 'mouseleave-box-select', 'mouseleave.wsItem', 'div.option-box span.box-select-item', function (event) {
                    defaultParams = params;
                    methods.changeTempImage.call($item, '', '', true);
                    return false;
                });
                ws.initEventHandler($item, 'shortcut-payment', 'click.wsItem', 'a.shortcut-payment', function (event) {
                    var that = $(this);
                    methods.shortcutPayment.call($item,that.data('role'),that.data('seller'));
                    return false;
                });




                $item.trigger($.Event(events.afterInit));
            });
        },
        changeTempImage: function (image, title, backup) {
            console.log(title + ' - ' + image);
            if (backup) {
                $('#detail-big-img').attr('src', varration_box.image);
                $('div.option-box label.label-option-box').html(varration_box.title);
            } else {
                varration_box.image = $('#detail-big-img').attr('src');
                varration_box.title = $('div.option-box label.label-option-box').html();
                $('#detail-big-img').attr('src', image);
                $('div.option-box label.label-option-box').html(title);
            }
        },
        changeVariation: function (variationOption, selectedValue) {
            var $item = $(this);
            var data = $item.data('wsItem');
            const value = variationOption.values[selectedValue];
            const name = variationOption.name;
            $('#error-' + variationOption.id).html("");
            console.log(variationOption);
            if (!value) {
                return;
            }
            if (variationOption.option_link && variationOption.sku) {
                const sku = variationOption.sku.filter(s => s.value_option === value);
                if (sku && sku[0].link) {
                    ws.loading(true);
                    window.location.assign(sku[0].link);
                    return;
                } else {
                    markOutofStock(true);
                    return;
                }
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
            console.log(currentVariations);
            currentVariations.push({name: name, value: value});
            if (currentVariations.length === data.params.variation_options.length) {
                const activeVariation = findVariation(params.variation_mapping, currentVariations);
                console.log(activeVariation);
                if (checkOutOfStock(activeVariation)) {
                    $.when.apply(this, deferredArrays).always(function () {
                        var queryParams = data.options.queryParams;
                        queryParams.sku = activeVariation.variation_sku;
                        if (!queryParams.seller) {
                            queryParams.seller = data.params.seller;
                        }
                        data.params.available_quantity = activeVariation.available_quantity;
                        data.params.quantity_sold = activeVariation.quantity_sold;
                        var quantityInstock = 0;
                        if (data.params.available_quantity) {
                            quantityInstock = data.params.quantity_sold ? data.params.available_quantity - data.params.quantity_sold : data.params.available_quantity;
                        }
                        $('#instockQuantity').html(quantityInstock);
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
                                    if (response.content.sellerCurrentId) {
                                        data.options.queryParams.seller = response.content.sellerCurrentId;
                                    }
                                    var content = $.extend({}, priceUpdateResponse, response.content || {});
                                    var temp = location.href.split('?');
                                    if (temp.length) {
                                        var url = temp[0];
                                        if (queryParams.seller) {
                                            url += '?seller=' + queryParams.seller;
                                        }
                                        if (queryParams.sku) {
                                            if (queryParams.seller) {
                                                url += '&sku=' + queryParams.sku;
                                            } else {
                                                url += '?sku=' + queryParams.sku;
                                            }
                                        }
                                        $('#quantity').val(1);
                                        $('#quantity').css('width', '30px');
                                        window.history.pushState(url, url, url);
                                    }
                                    updatePrice($item, content, data.ajaxed)
                                }
                            },
                            error: function () {
                                data.ajaxed = false;
                            }
                        }, true);
                    });
                }
            }
        },
        follow: function () {

        },
        shortcutPayment:function(type,sellerID) {
            var $item = $(this);
            $item.data('wsItem').params.seller = sellerID;
            paymentItem($item,type);
        },
        favorite: function () {
            var $item = $(this);
            var params = $item.data('wsItem').params;
            var data = {
                fingerprint: ws.getFingerprint(),
                sku: params.id,
                portal: params.type
            };
            setTimeout(function () {
                ws.ajax('/portal/f', {
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        if (response == 'Can not create favorite' || response == 'something wrong user uu!!!!!') {
                            $(".viewed-product").css("display", "none");
                        } else {
                            // console.log("done : cret fv s!!! ");
                        }
                    }
                }, false);
            }, 3000);
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
        changeQuantity: function () {
            console.log(defaultParams);
            var type = $(this).attr('data-href');
            var value = Number($('#quantity').val());
            var valueOld = Number($('#quantity').val());
            if (type === 'up') {
                value += 1;
            }
            if (type === 'down') {
                value -= 1;
            }
            value = value < 1 ? 1 : value;
            var numberInstock = 50;
            if (defaultParams.available_quantity) {
                numberInstock = Number(defaultParams.available_quantity) - Number(defaultParams.quantity_sold);
            }
            if (value > numberInstock) {
                $('#quantity').val(valueOld === value ? 1 : valueOld);
                return ws.notifyError(ws.t('You can not buy greater than {number}', {number: numberInstock}));
            }
            $('#quantity').val(value);
            $('#quantity').css('width', (value.toString().length * 10 + 20) + 'px');
        }
    };
    var deferredArray = function () {
        var array = [];
        array.add = function (callback) {
            this.push(new $.Deferred(callback));
        };
        return array;
    };
    var checkOutOfStock = function (activeVariation) {
        if (!activeVariation) {
            // ws.notifyError(ws.t("Out of stock"));
            markOutofStock(true);
            return false;
        } else {
            if (activeVariation.available_quantity > 0 && activeVariation.quantity_sold >= 0 && activeVariation.available_quantity - activeVariation.quantity_sold <= 0) {
                // ws.notifyError(ws.t("Out of stock"));
                markOutofStock(true);
                return false;
            }
        }
        markOutofStock(false);
        return true;
    };
    var markOutofStock = function (outOfStock) {
        if (outOfStock) {
            $("#outOfStock").css('display', 'block');
            $("#quantityGroup").css('display', 'none');
            $("#quoteBtn").css('display', 'inline-flex');
            $("#buyNowBtn").css('display', 'none');
            $("#addToCart").css('display', 'none');
            $("#installmentBtn").css('display', 'none');
        } else {
            $("#outOfStock").css('display', 'none');
            $("#quantityGroup").css('display', 'inline-flex');
            $("#quoteBtn").css('display', 'none');
            $("#buyNowBtn").css('display', 'block');
            $("#addToCart").css('display', 'initial');
            $("#installmentBtn").css('display', 'initial');
        }
    };
    var setUpDefaultOptions = function ($item) {
        var data = $item.data('wsItem');
        if (data.params.variation_mapping.length === 0) {
            // return;
        }
        var activeVariation = [];
        var sku = data.params.sku;
        if (sku !== undefined) {
            activeVariation = data.params.variation_mapping.filter(m => m.variation_sku === sku);
        }
        // console.log(activeVariation);
        if (activeVariation.length === 0) {
            var optionTemp = [];
            $.each(data.params.variation_options, function (k, v) {
                if (v.option_link && v.value_current) {
                    optionTemp.push({
                        name: v.name,
                        value: v.value_current,
                    });
                }
            });
            if (optionTemp.length > 0) {
                activeVariation.push({
                    options_group: optionTemp
                });
            } else {
                return;
            }
            // activeVariation = data.params.variation_mapping[0];
        }
        // console.log(activeVariation);
        var images = data.params.images;
        $.each(activeVariation[0].options_group, function (index, group) {
            for (var i = 0; i < data.params.variation_options.length; i++) {
                var variation_options = data.params.variation_options[i];
                if (group.name === variation_options.name) {
                    var values = variation_options.values;
                    for (var j = 0; j < values.length; j++) {
                        if (values[j] === group.value) {
                            var $input = findInput($item, variation_options);
                            var type = $input.attr('type');
                            currentVariations.push({name: group.name, value: group.value});
                            if (type === 'spanList' && $input.length > 0 && typeof $input[j] !== 'undefined') {
                                $('span[type=spanList]').parent().removeClass('active');
                                $('span[tabindex=' + j + ']').parent().addClass('active');
                                $('#label_' + variation_options.id).html(group.name + ': ' + group.value);
                            } else if (type === 'dropDown' && $input.length > 0) {
                                $input.val(j);
                            }
                            if (variation_options.images_mapping.length > 0 && activeVariation[0].variation_sku) {
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
    var checkVariationOutStock = function (variation, variation_mapping, variationcurrent) {
        var itemTemp = [];
        variation_mapping.forEach(function (entry) {
            var temp = 0;
            for (var i = 0; i < variationcurrent.length; i++) {
                for (var j = 0; j < entry.options_group.length; j++) {
                    if (variationcurrent[i]['name'] === entry.options_group[j]['name'] && variationcurrent[i]['value'] === entry.options_group[j]['value']) {
                        temp = temp + 1;
                    }
                }
            }
            if (temp === variationcurrent.length && (entry.quantity_sold - entry.available_quantity) !== 0) {
                itemTemp.push(entry);
            }
        });
        $('div[rel=specifics] select option').attr("disabled", "disabled");
        itemTemp.forEach(function (datatemp) {
            var i = 0;
            datatemp.options_group.forEach(function (itemvarri) {
                // console.log(itemvarri);
                i++;
                if (i === -1) {
                    $("select[name='" + itemvarri.name.replace(/\'/g, "\\'") + "'] option").removeAttr("disabled");
                }
                // else if(i === (variationcurrent.length +1 )){
                //   txtHtml =  $("select[name='"+itemvarri.name+"']").html();
                // }else if(i === variationcurrent.length){
                //     $("select[name='"+itemvarri.name+"']").html(txtHtml);
                // }
                else {

                    $('select[name="' + itemvarri.name.replace(/\'/g, "\\'") + '"] option[value="' + itemvarri.value + '"]').removeAttr("disabled");
                }
            });
        });
        $("div[rel=specifics] select option[value='0']").removeAttr("disabled");
    };
    var updatePrice = function ($item, content, isCalling) {
        var data = $item.data('wsItem');
        var selection = 'div.' + data.options.priceCssSelection;
        $(selection).find('strong.text-orange').html(content.sellPrice);
        if (content.contentPrice) {
            $(selection).html(content.contentPrice);
        }
        if (content.salePercent > 0) {
            $('#sale-tag').html(content.salePercent + '% OFF');
            $('#sale-tag').css('display', 'block');
        } else {
            $('#sale-tag').html('--% OFF');
            $('#sale-tag').css('display', 'none');
        }
        if (content.sellPrice === 0) {
            markOutofStock(true);
            return;
        }
        if (content.sellPrice_origin) {
            $('#price_origin').html('$' + content.sellPrice_origin);
            $('#price_origin_local').html('$' + content.fees.product_price_text);
        }
        if (content.fees.purchase_fee_text && content.fees.purchase_fee) {
            $('#purchase_fee').html(content.fees.purchase_fee_text);
        }
        if (content.queryParams.sku !== undefined) {
            data.params.sku = content.queryParams.sku;
            $item.data('wsItem', data);
        }
        if (content.sellerCurrentName) {
            $('#seller_name').html(content.sellerCurrentName);
        }
        if (content.sellerMore) {
            $('#other-seller-div').css('display', 'block');
            $('#other-seller-div .owl-carousel').remove();
            var sellermore = '<div class="owl-carousel owl-theme">' + content.sellerMore + '</div>';
            $('#other-seller-div').append(sellermore);
            $(".owl-carousel").owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                dots: true,
                arrows: true,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 4
                    },
                    1000: {
                        items: 6
                    }
                }
            });
        } else {
            $('#other-seller-div').css('display', 'none');
            $('#other-seller').html("");
        }
    };
    var tester = function ($item) {
        var data = $item.data('wsItem');
    };
    var changeImage = function ($item, images) {
        $item.data('wsItem').params.images = images;
        var html = '';
        var imageMb = '';
        $.each(images, function (index, value) {
            if (index == 0)
                html += '<div class="item active">';
            else {
                html += '<div class="item">';
            }
            html += '<a href="javascript:void (0);"  onclick="changeBigImage(this)"  data-image="' + value.main + '" data-zoom-image="' + value.main + '"> ' +
                '<img src="' + value.main + '" width="100"/>' +
                '</a>';
            html += '</div>';
            imageMb += '<img src="' + value.main + '" />';
        });
        varration_box.image = images[0].main;
        varration_box.title = $('div.option-box label.label-option-box').html();
        $('#detail-slider').html(html);
        $('.mb-slide-image').html(imageMb);
        $('#detail-big-img').attr('src', images[0].main);
        $('#detail-big-img').attr('data-zoom-image', images[0].main);
        $('#detail-slider .active a').click();
    };
    var watchVariationOptions = function ($item, variationOption) {
        var $input = findInput($item, variationOption);
        var type = $input.attr('type');
        if (type === 'spanList') {
            $input.on('click.wsItem', function (e) {
                $('span[type=spanList]').parent().removeClass('active');
                $(this).parent().addClass('active');
                var index = $(this).attr('tabindex');
                $('#label_' + variationOption.id).html(variationOption.name + ': ' + variationOption.values[index]);
                methods.changeVariation.call($item, variationOption, index);
            });
        } else {
            $input.on('change.wsItem', function (e) {
                var value = $(this).val();
                if (!value) {
                    currentVariations = currentVariations.filter(c => c.name !== variationOption.name);
                    return;
                }
                methods.changeVariation.call($item, variationOption, value);
            });
        }
    };
    var checkValidVariation = function ($item, variationOption) {

    };
    var findVariation = function (mapping, options) {
        console.log(options);
        return $.grep(mapping, function (i) {
            return JSON.stringify(i.options_group.sort(sortBy('name'))) == JSON.stringify(options.sort(sortBy('name')));
        })[0];
    };
    var findInput = function ($item, variationOption) {
        var id = variationOption.id;
        var selection = ' [data-ref=' + id + ']'.toLowerCase();
        var $input = $(selection);
        if ($input.length && $input[0].tagName.toLowerCase() === 'div') {
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
    var paymentItem = function ($item, type) {
        var $data = $item.data('wsItem');
        var quantity = $('#quantity').val();
        if (quantity < 1) {
            return ws.notifyError(ws.t('You can not buy lesser than {number}', {number: '1'}));
        }

        if ($data.params.variation_options.length > 0 && currentVariations.length !== $data.params.variation_options.length) {
            for (let ind = 0; ind < $data.params.variation_options.length; ind++) {
                var check = false;
                for (let ind_2 = 0; ind_2 < currentVariations.length; ind_2++) {
                    if (currentVariations[ind_2].name === $data.params.variation_options[ind].name) {
                        check = true;
                    }
                }
                if (!check) {
                    $('#error-' + $data.params.variation_options[ind].id).html(ws.t('Please select {variation}', {variation: $data.params.variation_options[ind].name}));
                }
            }
            return false;
        }

        var params = $data.params;
        var image = params.images[0] ? params.images[0].main : '/img/no_image.png';
        var item = {
            source: params.type,
            id: params.id,
            sku: params.sku,
            sellerId: params.seller,
            quantity: quantity,
            image: typeof image !== "string" ? image[0] : image,
        };
        
        var link = location.href;
        var $ajaxOptions = {
            type: 'POST',
            dataType: 'json',
            data: {item: item, type: type, link_payment: link},
            success: function (response) {
                if (response.success) {
                    var url = response.data || null;
                    if (url !== null && url !== undefined) {
                        ws.redirect(url);
                        return false
                    }
                } else {
                    ws.notifyError(response.message);
                }
            }
        };
        ws.ajax($data.options.paymentUrl, $ajaxOptions, true);
    }
})(jQuery);
var changeBigImage = function (e) {
    $('#detail-slider div.item').removeClass('active');
    $(e).parent().addClass('active');
};
var viewMoreSeller = function (more) {
    if (more) {
        $('[data-href=more_seller]').css('display', 'block');
        $('#HideSellerBtn').css('display', 'block');
        $('#viewMoreSellerBtn').css('display', 'none');
    } else {
        $('[data-href=more_seller]').css('display', 'none');
        $('#HideSellerBtn').css('display', 'none');
        $('#viewMoreSellerBtn').css('display', 'block');
    }
};
