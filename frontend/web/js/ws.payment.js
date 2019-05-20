ws.payment = (function ($) {
    var defaults = {
        page: undefined,
        payment_type: undefined,
        carts: [],
        use_xu: 0,
        bulk_point: 0,
        coupon_code: undefined,
        discount_detail: [],
        total_discount_amount: 0,
        currency: 'vnđ',
        total_amount: 0,
        total_amount_display: 0,
        customer_name: undefined,
        customer_email: undefined,
        customer_phone: undefined,
        customer_address: undefined,
        customer_postcode: undefined,
        customer_country: undefined,
        customer_city: undefined,
        customer_district: undefined,
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
        transaction_code: undefined,
        transaction_fee: 0
    };
    var pub = {
        payment: {},
        options: {},
        methods: [],
        shipping: {
            buyer_email: '',
            buyer_phone: '',
            buyer_name: '',
            buyer_province_id: '',
            buyer_district_id: '',
            receiver_email: '',
            receiver_phone: '',
            receiver_name: '',
            receiver_province_id: '',
            receiver_district_id: '',
            other_receiver : false,
            note_by_customer : '',
            save_my_address : '',
            enable_buyer: '',

        },
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
            $('#other-receiver').click(function () {
                if(!pub.shipping.other_receiver){
                    pub.shipping.other_receiver = true;
                    $('#other-receiver i').addClass('text-info');
                    $('#other-receiver svg').addClass('text-info');
                    $('#receiver-form').css('display','block');
                }else {
                    pub.shipping.other_receiver = false;
                    $('#other-receiver i').removeClass('text-info');
                    $('#other-receiver svg').removeClass('text-info');
                    $('#receiver-form').css('display','none');
                }
            });
            $('.checkout-step li').click(function () {
                var step = $(this)[0].firstElementChild.innerHTML;
                if($('#step_checkout_'+step).length === 1){
                    $('.checkout-step li').removeClass('active');
                    $(this).addClass('active');
                    $('#step_checkout_1').css('display','none');
                    $('#step_checkout_2').css('display','none');
                    $('#step_checkout_3').css('display','none');
                    $('#step_checkout_'+step).css('display','block');
                }
            });
            $('#btn-next-step3').click(function () {
                if(pub.getInfoFormShipping()){
                    console.log(pub.shipping);
                    $('#step_checkout_1').css('display','none');
                    $('#step_checkout_2').css('display','none');
                    $('#step_checkout_3').css('display','block');
                    window.scrollTo(0, 0);
                }
            });
            $('input[name=check-member]').click(function () {
                var value = $(this).val();
                if(value === 'new-member'){
                    $('div[data-merge=signup-form]').css('display','block');
                }else {
                    $('div[data-merge=signup-form]').css('display','none');
                }
            });
            $('input').change(function () {
                var name = $(this).attr('name');
                $('#'+name+'-error').html('');
            });
            $('#loginToCheckout').click(function () {
                ws.loading(true);
                var typeLogin = $('input[name=check-member]:checked').val();
                var loginForm = {};
                var SignupForm = {};
                var url = 'checkout.html';
                if(typeLogin === 'new-member'){
                    SignupForm = {
                        email: $('input[name=email]').val(),
                        password: $('input[name=password]').val(),
                        replacePassword: $('input[name=replacePassword]').val(),
                        first_name: $('input[name=first_name]').val(),
                        last_name: $('input[name=last_name]').val(),
                        phone: $('input[name=phone]').val(),
                    };
                    url = 'checkout/signup.html';
                }else {
                    loginForm = {
                        loginId: $('input[name=email]').val(),
                        password: $('input[name=password]').val(),
                        rememberMe: $('input[name=rememberMe]').val(),
                    };
                    url = 'checkout/login.html';
                }
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        LoginForm: loginForm,
                        SignupForm: SignupForm,
                        rel: location.href,
                    },
                    success: function (result) {
                        if (result.success) {
                            window.location.reload();
                        } else {
                            ws.loading(false);
                            $('label[data-href]').html('');
                            $.each(result.data, function (k,v) {
                                $('#'+k+'-error').html(v[0]);
                            })
                        }
                    }
                });
            });
        },
        selectMethod: function (providerId, methodId, bankCode) {
            console.log('selected providerId:' + providerId + ' methodId:' + methodId + ' bankCode:' + bankCode);
            pub.payment.payment_provider = providerId;
            pub.payment.payment_method = methodId;
            pub.payment.payment_bank_code = bankCode;
            if (methodId === 25) {
                bankCode = 'VCB';
                pub.methodChange(true);
            }
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
        getInfoFormShipping: function (){
            pub.shipping.buyer_name = $('#shippingform-buyer_name').val();
            pub.shipping.buyer_phone = $('#shippingform-buyer_phone').val();
            pub.shipping.buyer_email = $('#shippingform-buyer_email').val();
            pub.shipping.buyer_province_id = $('#shippingform-buyer_province_id').val();
            pub.shipping.buyer_district_id = $('#shippingform-buyer_district_id').val();
            pub.shipping.receiver_name = $('#shippingform-receiver_name').val();
            pub.shipping.receiver_phone = $('#shippingform-receiver_phone').val();
            pub.shipping.receiver_email = $('#shippingform-receiver_email').val();
            pub.shipping.receiver_province_id = $('#shippingform-receiver_province_id').val();
            pub.shipping.receiver_district_id = $('#shippingform-receiver_district_id').val();
            pub.shipping.note_by_customer = $('#shippingform-note_by_customer').val();
            pub.shipping.save_my_address = $('#shippingform-save_my_address:checked').val();
            if(!pub.shipping.buyer_name || !pub.shipping.buyer_phone || !pub.shipping.buyer_email || !pub.shipping.buyer_province_id || !pub.shipping.buyer_district_id){
                alert('Vui lòng nhập đầy đủ thông tin người mua');
                return false;
            }
            if(pub.shipping.other_receiver){
                if(!pub.shipping.receiver_name || !pub.shipping.receiver_phone || !pub.shipping.receiver_email || !pub.shipping.receiver_province_id || !pub.shipping.receiver_district_id){
                    alert('Vui lòng nhập đầy đủ thông tin người nhận');
                    return false;
                }
            }
            return true;
        },
        methodChange: function (isNew) {
            isNew = isNew || false;
            var method = '';
            var current_item = {};

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
            console.log(method);
            console.log(current_item);
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

            if (pub.payment.carts.length === 0) {
                return;
            }
            var data = pub.payment;
            delete data.ga;
            ws.ajax('/payment/discount/check-promotion', {
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
                $('input[name=couponCode]').val('');
                pub.payment.coupon_code = undefined;
            } else {
                $('input[name=couponCode]').val(code);
                pub.payment.coupon_code = code;
            }
            pub.checkPromotion();
        },
        login: function ($form) {
            var loginForm = $($form);
        },
        walletLogin: function () {

        },
        process: function () {
            if (!pub.getInfoFormShipping()){
                return;
            }
            var $termAgree = $('input#termCheckout').is(':checked');
            if (!$termAgree) {
                alert('Bạn phải đồng ý với điều khoản weshop');
                return;
            }
            ws.loading(true);
            ws.ajax('/payment/payment/process', {
                dataType: 'json',
                type: 'post',
                data: {payment: pub.payment, shipping: {enable_buyer: false}},
                success: function (response, textStatus, xhr) {
                    console.log(response);
                    if (response.success) {
                        ws.loading(false);
                        var data = response.data;
                        var code = data.code.toUpperCase() || '';
                        var method = data.method.toUpperCase();
                        if (method === 'POPUP') {
                            var type = data.provider.toUpperCase() || null;
                            if (type === 'WALLET') {
                                var $otp = $('#otp-confirm');
                                $otp.modal('show').find('#modalContent').load(data.checkoutUrl);
                            }
                        } else {
                            ws.loading(false);
                            $('span#transactionCode').html(code);
                            $('div#checkout-success').modal('show');
                            ws.initEventHandler('checkoutSuccess', 'nextPayment', 'click', 'button#next-payment', function (e) {
                                if (method === 'POST') {
                                    $(data.checkoutUrl).appendTo('body').submit();
                                } else {
                                    ws.redirect(data.checkoutUrl);
                                }
                            });
                            redirectPaymentGateway(data, 1000);
                        }

                    }

                }
            })

        },
        topUp: function () {

        },
        filterShippingAddress: function () {
            var $form = $('form.payment-form');
            if (!$form.length > 0) {
                return false;
            }
            var values = {};
            var formDataArray = $form.serializeArray();
            formDataArray.map(function (x) {
                if ('buyerPhone' === x.name || 'receiverPhone' === x.name) {
                    var val = $.trim(x.value);
                    values[x.name] = val.indexOf("0") === 0 ? val : '0' + val;
                    values[x.name] = val.replace('+84', '0');
                } else {
                    values[x.name] = $.trim(x.value);
                }
            });
            pub.shipping = formDataArray;
            return formDataArray;
        },
    };
    var updatePaymentByPromotion = function ($response) {
        var input = $('input[name=couponCode]');
        var $errorDiscount = $('span#discountErrors');
        $errorDiscount.css('display', 'none');
        if (!$response.success || pub.payment.coupon_code in $response.errors) {
            console.log($response.errors[pub.payment.coupon_code]);
            var error = $response.errors[pub.payment.coupon_code];
            $errorDiscount.css('display', 'flex');
            $errorDiscount.html(error);
        }
        var box = $('#billingBox');
        var discountBox = box.find('li#discountPrice');
        discountBox.css('display', 'none');
        if ($response.details.length > 0 && $response.discount > 0) {
            pub.payment.discount_detail = $response.details;
            pub.payment.total_discount_amount = $response.discount;
            pub.payment.total_amount_display = pub.payment.total_amount - pub.payment.total_discount_amount;
            discountBox.css('display', 'flex');
            discountBox.find('div.right').html('- ' + ws.numberFormat(pub.payment.total_discount_amount, -3) + ' ' + pub.payment.currency);
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
            text += '<div class="left"><div class="code">';
            $('#discountIpnputCoupon').css('display', 'flex');
            if (item.type === 'Coupon') {
                text += '<i class="fas fa-times text-danger remove"  title="Remove ' + item.code + '" onclick="ws.payment.changeCouponCode(\'' + item.code + '\')"></i>';
                $('#discountInputCoupon').css('display', 'none');
            }
            text += item.code + '<i class="fas fa-question-circle code-info" data-toggle="tooltip" data-placement="top" title="' + item.message + '" data-original-title="' + item.message + '"></i></div></div>';
            text += '<div class="right"> - ' + ws.numberFormat(item.value, -3) + ' ' + pub.payment.currency + '</div>';
            text += '</li>';
        });
        return text;
    };
    var redirectPaymentGateway = function (rs, $timeOut) {
        runTime = setInterval(function () {
            var second = parseInt($("#countdown_payment").text());
            if (second > 0) {
                $("#countdown_payment").text(second - 1);
            } else {
                if (rs.method == 'POST') {
                    $(rs.checkoutUrl).appendTo('body').submit();
                    clearInterval(runTime);
                } else {
                    window.location.href = rs.checkoutUrl;
                    clearInterval(runTime);
                }
            }
        }, $timeOut);
    }
    return pub;
})(jQuery);