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
    };

    var eventHandlers = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $cart = $(this);
                if ($cart.data('wsCart')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                $cart.data('wsCart', {settings: settings});
                ws.initEventHandler($cart, 'updateQuantity', 'click.wsCart', 'button.button-quantity-up,button.button-quantity-down', function (event) {
                    var self = $(this);
                    var options = getQuantityInputOptions($(self.data('update')));
                    if (options.max === '' || options.max < options.value) {
                        alert('can not update cart');
                    }
                    var data = {};
                    data.id = options.id;
                    data.quantity = options.value + 1;
                    methods.update.call($cart, data)
                });
                ws.initEventHandler($cart, 'deleteCart', 'click', 'a.del', function (event) {

                })
            });
        },
        refresh: function () {

        },
        add: function (params) {

        },
        update: function ($data) {
            var $cart = $(this);
            var data = $cart.data('wsCart');
            var $ajaxOptions = {
                dataType: 'json',
                method: 'post',
                data: $data,
                success: function (response, textStatus, xhr) {
                    updateItem(response)
                }
            };
            ws.ajax(data.settings.updateUrl, $ajaxOptions, true);

        },
        hiden: function ($key) {

        },
        remove: function ($key) {

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
    var getQuantityInputOptions = function ($input) {
        return {
            id: $input.attr('id'),
            value: Number($input.attr('value')),
            operator: 'up',
            min: $input.data('min'),
            max: $input.data('max'),
        }
    };
    var updateItem = function ($data) {
        console.log($data)
    };
})(jQuery);