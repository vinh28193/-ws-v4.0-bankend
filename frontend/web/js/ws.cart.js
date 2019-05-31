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
        paymentUrl: undefined,
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $cart = $(this);
                if ($cart.data('wsCart')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});


                $cart.data('wsCart', {
                    settings: settings
                });

                ws.initEventHandler($cart, 'update', 'click.wsCart', 'button.button-quantity-up,button.button-quantity-down', function (event) {
                    event.preventDefault();
                    var $item = $(this);
                    var id = $item.data('parent');
                    var key = {
                        id: $item.data('id'),
                        sku: $item.data('sku')
                    };
                    var targetSelection = 'input[name=cartItemQuantity][data-parent="' + id + '"][data-id="' + key.id + '"][data-sku="' + key.sku + '"]';
                    var $target = $(targetSelection);
                    var options = getQuantityInputOptions($target);
                    var operator = $item.data('operator');
                    if (options.max === '' || options.max < options.value) {
                        ws.sweetalert('Không thể thay đổi số lượng', 'error');
                    }
                    var data = {type: $item.data('type'), id: id, key: key};
                    var param = {quantity: options.value};
                    if (operator === 'up') {
                        param.quantity += 1;
                        if (param.quantity > options.max && options.max !== '' && options.max > options.value) {
                            param.quantity = options.max;
                            $target.val(param.quantity);
                            ws.sweetalert('Bạn không thể mua quá: ' + options.max, 'error');
                            return;
                        }
                    } else {
                        param.quantity -= 1;
                        if (param.quantity < 1) {
                            param.quantity = 1;
                            $target.val(1);
                            ws.sweetalert('Bạn không thể mua dưới 1', 'error');
                            return;
                        }
                    }
                    data.param = param;
                    methods.update.call($cart, data);
                    return false;
                });
                ws.initEventHandler($cart, 'type', 'keyup.wsCart', 'input[name=cartItemQuantity]', function (event) {
                    var $item = $(this);
                    var options = getQuantityInputOptions($item);
                    var data = {
                        id: options.key,
                        key: {id: options.id, sku: options.sku}
                    };
                    var param = {quantity: Number($item.val())};
                    if (param.quantity < 1) {
                        param.quantity = 1;
                        $item.val(1);
                        ws.sweetalert('Bạn không thể mua dưới 1', 'error');
                    } else if (options.max !== '' && param.quantity >= options.max) {
                        param.quantity = options.max;
                        $item.val(options.max);
                        ws.sweetalert('Bạn không thể mua quá: ' + options.max, 'error');
                    }
                    data.param = param;
                    methods.update.call($cart, data);
                    return false;
                });
                ws.initEventHandler($cart, 'remove', 'click.wsCart', 'a.delete-item', function (event) {
                    var $elem = $(this);
                    var param = {
                        type: $elem.data('type'),
                        id: $elem.data('parent'),
                        key: {id: $elem.data('id'), sku: $elem.data('sku')},
                    };
                    methods.remove.call($cart, param);
                });
                ws.initEventHandler($cart, 'continue', 'click.wsCart', 'button.btn-continue', function (event) {
                    methods.continue.apply($cart);
                });

                ws.initEventHandler($cart, 'payment', 'click.wsCart', 'button.btn-payment', function (event) {
                    methods.payment.apply($cart);
                });
                ws.initEventHandler($cart, 'cartOrder', 'change.wsCart', 'input[name=cartOrder]', function (event) {
                    event.preventDefault();
                    var $input = $(this);
                    // var data = {param: {key: $input.val()}, selected: $input.is(':checked')};
                    var data = {id: $input.val(), selected: $input.is(':checked')};
                    methods.watch.call($cart, data);
                    return false;
                });
                // ws.initEventHandler($cart, 'cartProduct', 'change.wsCart', 'input[name=cartProduct]', function (event) {
                //     event.preventDefault();
                //     var $input = $(this);
                //     var data = {id: $input.val(), selected: $input.is(':checked')};
                //     // if (data.selected === false) {
                //     //     selected = selected.filter(s => s !== data.key);
                //     // } else {
                //     //     selected.push(data.key);
                //     // }
                //     // console.log(selected);
                //     methods.watch.call($cart, data);
                //     return false;
                // });
            });
        },
        items: function () {

        },
        refresh: function () {

        },
        add: function ($type) {
        },
        watch: function ($param) {
            var $cart = $(this);
            var container = '#' + $cart.attr('id');
            ws.ajax('/checkout/cart/selection', {
                dataType: 'json',
                method: 'post',
                data: $param,
                success: function () {
                    $.pjax.reload({container: container});
                }
            });

        },
        update: function (param) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var container = '#' + $cart.attr('id');
            var $ajaxOptions = {
                dataType: 'json',
                method: 'post',
                data: param,
                success: function (response, textStatus, xhr) {
                    if (response.success) {
                        $.pjax.reload({container: container});
                    } else {
                        ws.sweetalert(response.message, 'error');
                    }
                }
            };
            ws.ajax(data.settings.updateUrl, $ajaxOptions, true);

        },
        hiden: function ($key) {

        },
        remove: function (param) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var container = '#' + $cart.attr('id');
            var $ajaxOptions = {
                dataType: 'json',
                method: 'post',
                data: param,
                success: function (response, textStatus, xhr) {
                    if (response.success) {
                        // updateItem(response);
                        $.pjax.reload({container: container});
                        var countItems = response.countItems || false;
                        if (countItems) {
                            $('#cartBadge').html(countItems);
                        }
                    } else {
                        ws.sweetalert(response.message, 'error');
                    }

                }
            };
            ws.ajax(data.settings.removeUrl, $ajaxOptions, true);
        },
        continue: function () {
            ws.goback();
        },
        payment: function () {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var keys = [];

            $.each(filterCartItems($cart), function (i, $input) {
                keys.push($($input).val());
            });
            ws.ajax(data.settings.paymentUrl, {
                dataType: 'json',
                method: 'post',
                data: {carts: keys},
                success: function (response) {
                    if (response.success) {
                        var url = response.data || null;
                        if (url === null) {
                            alert('action can not complete');
                            return false;
                        }
                        ws.redirect(url);
                    } else {
                        alert(response.message);
                    }
                }
            });
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
    var updateTotalPrice = function ($cart) {
        totalAmount = 0;
        $.each(filterCartItems($cart), function (i, input) {
            console.log($(input).data('price'));
            totalAmount += $(input).data('price');
        });
        $('span#totalCartPrice').html(ws.numberFormat(totalAmount));
    };
    var updateNavBage = function ($count) {
        $('contentCart').html($count);
    };
    var getQuantityInputOptions = function ($input) {
        return {
            key: $input.data('parent'),
            type: $input.data('type'),
            id: $input.data('id'),
            sku: $input.data('sku'),
            value: Number($input.attr('value')),
            min: $input.data('min'),
            max: $input.data('max'),
        }
    };
    var filterCartItems = function ($cart) {
        return $cart.find('input[name=cartOrder]:checked');
        // var $selected = [];
        // $.each($cart.find('input[name=cartOrder]:checked'), function (i, cartOrder) {
        //     var $cartOrder = $(cartOrder);
        //     var item = {
        //         key: $cartOrder.val(),
        //         products: []
        //     };
        //     var $parent = $('ul[data-key=' + item.key + ']');
        //     $.each($parent.find('input[name=cartProduct][data-parent=' + item.key + ']:checked'), function (i, cartProduct) {
        //         var $product = $(cartProduct);
        //         item.products.push({id: $product.data('id'), sku: $product.data('sku')})
        //     });
        //     console.log(item);
        // });
        // return false;
    };
    var getParamFromElement = function ($element) {
        return {
            key: $element.data('parent') || null,
            id: $element.data('id') || null,
            sku: $element.data('sku') || null,
        }
    }
    var updateItem = function ($data) {
        console.log($data)
    };
})(jQuery);