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
        items: []
    };

    var defaultItem = {
        key: undefined,
        selected: undefined,
        name: undefined,
        type: undefined,
        originLink: undefined,
        link: undefined,
        imageSrc: undefined,
        provider: undefined,
        variation: undefined,
        condition: undefined,
        quantity: 0,
        availableQuantity: 0,
        soldQuantity: 0,
        weight: undefined,
        amount: 0,
        localAmount: 0,
        localDisplayAmount: undefined,
    };

    var methods = {
        init: function (items, options) {
            return this.each(function () {
                var $cart = $(this);
                if ($cart.data('wsCart')) {
                    return;
                }
                items = items.map(i => $.extend({}, defaultItem, i || {}));
                var settings = $.extend({}, defaults, options || {});
                $cart.data('wsCart', {
                    items: items,
                    settings: settings
                });
                ws.initEventHandler($cart, 'update', 'click.wsCart', 'button.button-quantity-up,button.button-quantity-down', function (event) {
                    methods.update.call($cart, $(this));
                });
                ws.initEventHandler($cart, 'remove', 'click.wsCart', 'a.delete-item', function (event) {
                    var key = $(this).data('key');
                    if (key === undefined) {
                        return false;
                    }
                    methods.remove.call($cart, key)

                });
                ws.initEventHandler($cart, 'continue', 'click.wsCart', 'button.btn-continue', function (event) {
                    methods.continue.apply($cart);
                });
                ws.initEventHandler($cart, 'payment', 'click.wsCart', 'button.btn-payment', function (event) {
                    methods.payment.apply($cart);
                });
                ws.initEventHandler($cart, 'selected', 'change.wsCart', 'input[name=cartItems]', function (event) {
                    console.log($(this));
                    console.log(filterCartItems($cart));
                });
            });
        },
        refresh: function () {

        },
        add: function ($type) {
        },
        watch: function (key) {
            var $cart = $(this);
        },
        update: function ($item) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var options = getQuantityInputOptions($($item.data('update')));
            var operator = $item.data('operator');
            if (options.max === '' || options.max < options.value) {
                alert('can not update cart');
            }
            var param = {};
            param.id = options.id;
            param.quantity = options.value;
            if (operator === 'up') {
                param.quantity += 1;
                if (param.quantity > options.max && options.max !== '' && options.max > options.value) {
                    param.quantity = options.max;
                    alert('you can buy greater than ' + options.max)
                }
            } else {
                param.quantity -= 1;
                if (param.quantity < 1) {
                    param.quantity = 1;
                    alert('you can buy less than 1')
                }
            }
            var container = '#' + $cart.attr('id');
            var $ajaxOptions = {
                dataType: 'json',
                method: 'post',
                data: param,
                success: function (response, textStatus, xhr) {
                    // updateItem(response);
                    $.pjax.reload({container: container});
                }
            };
            ws.ajax(data.settings.updateUrl, $ajaxOptions, true);

        },
        hiden: function ($key) {

        },
        remove: function ($key) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var container = '#' + $cart.attr('id');
            var $ajaxOptions = {
                dataType: 'json',
                method: 'post',
                data: {id: $key},
                success: function (response, textStatus, xhr) {
                    // updateItem(response);
                    $.pjax.reload({container: container});
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
            console.log(keys);

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

    };
    var getQuantityInputOptions = function ($input) {
        return {
            id: $input.attr('id'),
            value: Number($input.attr('value')),
            min: $input.data('min'),
            max: $input.data('max'),
        }
    };
    var filterCartItems = function ($cart) {
        return $cart.find('input[name="cartItems"]:checked');
    };
    var updateItem = function ($data) {
        console.log($data)
    };
})(jQuery);