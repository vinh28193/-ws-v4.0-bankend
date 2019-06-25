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
        uuid: ws.getFingerprint(),
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

                if (!settings.uuid) {
                    methods.checkUuid.apply($cart);
                }
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
                        ws.notifyInfo(ws.t('Cannot change quantity'), ws.t('Error'));
                    }
                    var data = {type: $item.data('type'), id: id, key: key};
                    var param = {quantity: options.value};
                    if (operator === 'up') {
                        param.quantity += 1;
                        if (param.quantity > options.max && options.max !== '' && options.max > options.value) {
                            param.quantity = options.max;
                            $target.val(param.quantity);
                            ws.notifyError(ws.t('You can not buy greater than {number}', {number: options.max}), ws.t('Error'));
                            return;
                        }
                    } else {
                        param.quantity -= 1;
                        if (param.quantity < 1) {
                            param.quantity = 1;
                            $target.val(1);
                            ws.notifyError(ws.t('You can not buy lesser than {number}', {number: 1}), ws.t('Error'));
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
                        ws.notifyError(ws.t('You can not buy lesser than {number}', {number: 1}), ws.t('Error'));
                    } else if (options.max !== '' && param.quantity >= options.max) {
                        param.quantity = options.max;
                        $item.val(options.max);
                        ws.notifyError(ws.t('You can not buy greater than {number}', {number: options.max}), ws.t('Error'));
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

                ws.initEventHandler($cart, 'installmentPayment', 'click.wsCart', 'button#installmentBtn', function (event) {
                    event.preventDefault();
                    methods.payment.apply($cart, ['installment']);
                });

                ws.initEventHandler($cart, 'shoppingPayment', 'click.wsCart', 'button#shoppingBtn', function (event) {
                    event.preventDefault();
                    methods.payment.apply($cart, ['shopping']);
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
        checkUuid: function () {
            var $cart = $(this);
            var container = '#' + $cart.attr('id');
            var deferredArrays = deferredArray();
            $.when.apply(this, deferredArrays).always(function () {
                ws.ajax('/checkout/cart/check-uuid', function (res) {
                    if (res) {
                        $.pjax.reload({container: container});
                    }
                }, true);
            });

        },
        refresh: function () {
            var $cart = $(this);
            var container = '#' + $cart.attr('id');
            $.pjax.reload();
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
                        ws.notifyError(response.message, ws.t('Error'));
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
                        var countItems = response.countItems || false;
                        if (countItems) {
                            ws.setCartBadge(countItems);
                        }
                        $.pjax.reload({container: container});
                    } else {
                        ws.notifyError(response.message, ws.t('Error'));
                    }

                }
            };
            ws.ajax(data.settings.removeUrl, $ajaxOptions, true);
        },
        continue: function () {
            ws.goback();
        },
        payment: function (type) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var keys = [];

            $.each(filterCartItems($cart), function (i, $input) {
                // keys.push($($input).val());
                keys.push($($input).data('key'));
            });
            ws.ajax(data.settings.paymentUrl, {
                dataType: 'json',
                method: 'post',
                data: {carts: keys, type: type},
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        var url = response.data || null;
                        if (url === null) {
                            ws.notifySuccess(ws.t('Action cannot complete'));
                            return false;
                        }
                        ws.redirect(url);
                    } else {
                        ws.notifyError(response.message);
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
    var deferredArray = function () {
        var array = [];
        array.add = function (callback) {
            this.push(new $.Deferred(callback));
        };
        return array;
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
        console.log($cart.attr('id'));
        return $cart.find('div.cart-item');
        // return $cart.find('input[name=cartOrder]:checked');
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

$('input[name=checkCar]').change(function() {
    var values = [];
    {
        $("input[name=checkCar]:checked").each(function() {
            values.push($(this).val());
        });
    }
});