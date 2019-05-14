ws.payment = (function ($) {
    var defaults = {
        page: undefined,
        orders: [],
        use_xu: 0,
        bulk_point: 0,
        coupon_code: undefined,
        discount_detail: [],
        total_discount_amount: 0,
        currency: 'vnđ',
        total_amount: 0,
        total_amount_display: 0,
        payment_bank_code: undefined,
        payment_method: undefined,
        payment_method_name: undefined,
        payment_provider: undefined,
        payment_provider_name: undefined,
        payment_token: undefined,
        installment_bank: undefined,
        installment_method: undefined,
        installment_month: undefined,
        instalment_type: undefined,
        ga: undefined,
        otp_verify_method: 1,
        shipment_options_status: 1,
        transaction_id: undefined,
        transaction_fee: 0
    };
    var pub = {
        payment: {},
        options: {},
        methods: [],
        shipping: [],
        init: function (options) {
            pub.payment = $.extend({}, defaults, options || {});
            pub.payment.currency = 'vnd';
            if (pub.payment.page !== 4) {
                setTimeout(function () {
                    pub.checkPromotion();
                }, 300)
            }
            ws.initEventHandler($('div#discountCoupon'), 'applyCouponCode', 'click', 'button#applyCouponCode', function (e) {
                var $input = $(this).parents('div.discount-input').find('input[name="couponCode"]');
                if ($input.length > 0 && $input.val() !== '') {
                    pub.payment.coupon_code = $input.val();
                    pub.checkPromotion();
                }
            });
        },
        selectMethod: function (providerId, methodId, bankCode) {
            console.log('selected providerId:' + providerId + ' methodId:' + methodId + ' bankCode:' + bankCode);
            pub.payment.payment_provider = providerId;
            pub.payment.payment_method = methodId;
            pub.payment.payment_bank_code = bankCode;
            $.each($('li[rel=s_bankCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            if ($('#bank_code_' + bankCode + '_' + methodId).length > 0) {
                $('#bank_code_' + bankCode + '_' + methodId).find('span').addClass('active');
            }
            pub.checkPromotion();
        },
        registerMethods: function ($methods) {
            pub.methods = $methods;
            console.log('register ' + pub.methods.length + ' methods');
        },
        methodChange: function (isNew) {
            isNew = isNew || false;
            var method = '';
            var current_item = {};
            console.log('methodChange ' + (isNew === true ? 'true' : 'false'));
            if (isNew) {
                method = $('#bankOptions').val();
                pub.payment.payment_method = method;
                current_item = $.grep(pub.methods, function (element, index) {
                    return element.payment_method_id === method;
                })[0];
                if (!current_item || !current_item.paymentMethod.paymentMethodBanks || current_item.paymentMethod.paymentMethodBanks.length === 0) {
                    return false;
                }
                pub.payment.payment_bank_code = current_item.paymentMethod.paymentMethodBanks[0].paymentBank.code;
            } else {
                $('#bankOptions').val(pub.payment.payment_method);
                method = pub.payment.payment_method;
                current_item = $.grep(pub.methods, function (element, index) {
                    return element.payment_method_id === method;
                })[0];
                if (!current_item) {
                    return false;
                }
            }
            var html = '';
            $.each(current_item.paymentMethod.paymentMethodBanks, function (index, item) {
                html += '<li rel="s_bankCode" id="bank_code_' + item.paymentBank.code + '_' + current_item.payment_method_id + '" onclick="ws.payment.selectMethod(' + current_item.payment_provider_id + ',' + current_item.payment_method_id + ',\'' + item.paymentBank.code + '\')">\n' +
                    '<span class="' + (item.paymentBank.code === pub.payment.payment_bank_code ? "active" : "") + '"><img src="' + item.paymentBank.icon + '" alt="' + item.paymentBank.name + '" title="' + item.paymentBank.name + '"></span>' +
                    '</li>';
            });
            $('#atm_content').html(html);
            if ($('#bank_code_' + pub.payment.payment_bank_code + '_' + pub.payment.payment_method).length) {
                $.each($('li[rel=s_bankCode]'), function () {
                    $(this).removeClass('active');
                });
                $('#bank_code_' + pub.payment.payment_bank_code + '_' + pub.payment.payment_method).addClass('active');
            }
        },

        checkPromotion: function () {

            if (pub.payment.orders.length === 0) {
                return;
            }
            var data = pub.payment;
            delete data.ga;
            ws.ajax('/checkout/discount/check-promotion', {
                dataType: 'json',
                type: 'post',
                data: data,
                success: function (response, textStatus, xhr) {
                    updatePaymentByPromotion(response)
                }
            })

        },
        changeCouponCode: function (code) {
            if (pub.payment.coupon_code === code) {
                pub.payment.coupon_code = undefined;
            } else {
                pub.payment.coupon_code = code;
            }
            pub.checkPromotion();
        },
        createOrder: function () {

        },
        filterShippingAddress: function ($form) {

        },
    };
    var updatePaymentByPromotion = function ($response) {
        var input = $('input[name=couponCode]');
        if (!$response.success || pub.payment.coupon_code in $response.errors) {
            // console.log($response.errors[pub.payment.coupon_code]);
            // var error = $response.errors[pub.payment.coupon_code];
            // input.parents('div.form-group').addClass('has-danger');
            // input.parents.append('<div class="form-control-feedback">dadasd</div>');
            // console.log(error);
        }
        var box = $('#billingBox');
        var discountBox = box.find('li#discountPrice');
        discountBox.css('display', 'none');
        if ($response.details.length > 0 && $response.discount > 0) {
            pub.payment.discount_detail = $response.details;
            pub.payment.total_discount_amount = $response.discount;
            pub.payment.total_amount_display = pub.payment.total_amount - pub.payment.total_discount_amount;
            discountBox.css('display', 'flex');
            discountBox.find('div.right').html(ws.numberFormat(pub.payment.total_discount_amount, -3) + ' ' + pub.payment.currency);
            box.find('li#finalPrice').find('div.right').html(ws.numberFormat(pub.payment.total_amount_display, -3) + ' ' + pub.payment.currency);
            box.find('li[rel="detail"]').remove();
            box.prepend(initPromotionView(pub.payment.discount_detail));
        }

    };
    var initPromotionView = function ($detail) {
        var text = '';
        $.each($detail, function (key, item) {
            console.log(item);
            text += '<li rel="detail" data-key="' + item.id + '" data-code="' + item.code + '" data-type="' + item.type + '">';
            text += '<div class="left">';
            if (item.type === 'Coupon') {
                text += '<a href="javascript:void(0);" class="del-coupon"  onclick="ws.payment.changeCouponCode(\'' + item.code + '\')">' +
                    '<i class="fa fa-times-circle"></i> ' +
                    '</a>';
            }
            text += item.code + '<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="' + item.message + '" data-original-title="' + item.message + '"></i></div>';
            text += '<div class="right">' + item.value + ' ' + pub.payment.currency + '</div>';
            text += '</li>';
        });
        return text;
    };
    return pub;
})(jQuery);