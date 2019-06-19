ws.payment = (function ($) {
    var defaults = {
        page: undefined,
        uuid: ws.getFingerprint(),
        type: undefined,
        carts: [],
        use_xu: 0,
        bulk_point: 0,
        coupon_code: undefined,
        discount_detail: [],
        currency: 'VND',
        total_discount_amount: 0,
        total_order_amount: 0,
        totalAmount: 0,
        totalAmountDisplay: 0,
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
        bank_account: undefined,
        bank_name: undefined,
        issue_month: undefined,
        issue_year: undefined,
        expired_month: undefined,
        expired_year: undefined,
        installment_bank: undefined,
        installment_method: undefined,
        installment_month: undefined,
        instalment_type: undefined,
        ga: undefined,
        otp_verify_method: 1,
        shipment_options_status: 1,
        transaction_code: undefined,
        transaction_fee: 0,
        additionalFees: [],
    };
    var current_step = 2;
    var pub = {
        payment: {},
        options: {},
        methods: [],
        shipping: {},
        couriers: [],
        installmentParam: {
            calculator: undefined,
            originAmount: 0,
            banks: [],
            currentBank: {
                bankCode: undefined,
                bankName: undefined,
                bankIcon: undefined,
                paymentMethods: []
            },
            currentMethod: {
                paymentMethod: undefined,
                methodIcon: undefined,
                periods: []
            },
            currentPeriod: {
                amountByMonth: 0,
                amountFee: 0,
                amountFinal: 0,
                currency: undefined,
                month: 0,
                payerFlatFee: 0,
                payerInstallmentFlatFee: 0,
                payerInstallmentPercentFee: 0,
                payerPercentFee: 0,
            }
        },
        init: function (options) {
            pub.payment = $.extend({}, defaults, options || {});
            ws.initEventHandler($('div#discountCoupon'), 'applyCouponCode', 'click', 'button#applyCouponCode', function (e) {
                var $input = $(this).parents('div.discount-input').find('input[name="couponCode"]');
                if ($input.length > 0 && $input.val() !== '') {
                    pub.payment.coupon_code = $input.val();
                    pub.checkPromotion();
                }
            });

            pub.activePaymentStep(2);

            $('input[name=check-member]').click(function () {
                var value = $(this).val();
                if (value === 'new-member') {
                    $('div[data-merge=signup-form]').css('display', 'block');
                } else {
                    $('div[data-merge=signup-form]').css('display', 'none');
                }
            });

            $('#continueAsGuest').on('click', function (e) {
                $('.checkout-step li').removeClass('active');
                $('.checkout-step li').each(function (k, v) {
                    if (k === 1) {
                        $(v).addClass('active');
                    }
                });
                $('#step_checkout_1').css('display', 'none');
                $('#step_checkout_2').css('display', 'block');
                $('#step_checkout_3').css('display', 'none');
                window.scrollTo(0, 0);
            });
            $('#loginToCheckout').click(function () {
                ws.loading(true);
                var typeLogin = $('input[name=check-member]:checked').val();
                var loginForm = {};
                var SignupForm = {};
                var url = 'checkout.html';
                if (typeLogin === 'new-member') {
                    SignupForm = {
                        email: $('input[name=email]').val(),
                        password: $('input[name=password]').val(),
                        replacePassword: $('input[name=replacePassword]').val(),
                        first_name: $('input[name=first_name]').val(),
                        last_name: $('input[name=last_name]').val(),
                        phone: $('input[name=phone]').val(),
                    };
                    url = 'checkout/signup.html';
                } else {
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
                            $.each(result.data, function (k, v) {
                                $('#' + k + '-error').html(v[0]);
                            })
                        }
                    }
                });
            });
        },
        set: function (name, value) {
            pub.payment[name] = value;
        },
        get: function (name, defaultValue) {
            return pub.payment[name] || defaultValue;
        },
        selectMethod: function (providerId, methodId, bankCode, checkRequire = false) {
            console.log('selected providerId:' + providerId + ' methodId:' + methodId + ' bankCode:' + bankCode);
            pub.payment.payment_provider = providerId;
            pub.payment.payment_method = methodId;
            pub.payment.payment_bank_code = bankCode;
            if (bankCode === 'ATM_ONLINE') {
                pub.methodChange(false);
            }
            if (checkRequire === true) {
                pub.checkRequireField();
            }
            $.each($('li[rel=s_bankCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            var activeMethod = $('#bank_code_' + bankCode + '_' + methodId);
            if (activeMethod.length > 0) {
                activeMethod.find('span').addClass('active');
            }
            pub.checkPromotion();
        },
        checkRequireField: function () {
            ws.ajax('/payment/' + pub.payment.payment_provider + '/check-field', {
                dataType: 'json',
                type: 'post',
                data: pub.payment,
                success: function (response) {
                    console.log(response);
                }
            });
        },
        registerMethods: function ($methods) {
            pub.methods = $methods;
            console.log('register ' + pub.methods.length + ' methods');
        },
        calculatorShipping: function () {
            if (!pub.filterShippingAddress(false)) {
                return;
            }
            console.log(pub.shipping);
            if (!pub.shipping.buyer_district_id || !pub.shipping.buyer_province_id) {
                return;
            }
            ws.ajax('/payment/courier/calculator', {
                dataType: 'json',
                type: 'post',
                data: {payment: pub.payment, shipping: pub.shipping},
                success: function (response) {
                    console.log(response);
                    // if (response.success) {
                    //     var couriers = response.data;
                    //     console.log(couriers[0]);
                    //     pub.couriers = couriers;
                    //     initCourierView(couriers);
                    //     pub.courierChange(couriers[0]);
                    //     pub.checkPromotion();
                    // } else {
                    //     ws.notifyError(response.message);
                    // }
                }
            }, true);
        },
        calculateInstallment: function () {
            ws.ajax('/payment/' + pub.payment.payment_provider + '/calc', {
                dataType: 'json',
                type: 'post',
                data: pub.payment,
                success: function (response) {
                    var data = response.data;
                    var banks = data.methods || [];
                    var promotion = data.promotion || undefined;
                    pub.installmentParam.calculator = data.calculator;
                    pub.installmentParam.originAmount = data.origin || ws.showMoney(0);
                    initInstallmentBankView(banks);
                    ws.initEventHandler('calculateInstallment', 'periodChange', 'change', 'input[name=installmentPeriod]', function (e) {
                        pub.payment.installment_month = $(this).val();
                    });
                    if (promotion !== undefined) {
                        updatePaymentByPromotion(promotion)
                    }
                }
            }, true);
        },
        installmentBankChange: function (code) {
            console.log('selected bank :' + code);
            pub.payment.installment_bank = code;
            pub.installmentParam.currentBank = $.grep(pub.installmentParam.banks, function (x) {
                return String(x.bankCode) === String(code);
            })[0];
            $.each($('li[data-ref=i_bankCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            var isActive = $('li[data-ref=i_bankCode][data-code=' + code + ']');
            if (isActive.length > 0) {
                isActive.find('span').addClass('active');
            }
            var htmlMethod = [];
            $.each(pub.installmentParam.currentBank.paymentMethods, function (index, method) {
                var iActive = index === 0;
                if (iActive) {
                    pub.payment.installment_method = method.paymentMethod;
                    pub.installmentMethodChange(method.paymentMethod);
                }
                var $ele = '<li data-ref="i_methodCode" data-code="' + method.paymentMethod + '"  onclick="ws.payment.installmentMethodChange(\'' + method.paymentMethod + '\')"><span class="' + (iActive ? "active" : "") + '"><img src="' + method.methodIcon + '" alt="' + method.paymentMethod + '" title="' + method.paymentMethod + '"/></span></li>';
                htmlMethod.push($ele)
            });
            $('ul#installmentMethods').html(htmlMethod.join(''));
        },
        installmentMethodChange(code) {
            console.log('selected method :' + code);
            pub.payment.installment_method = code;
            pub.installmentParam.currentMethod = $.grep(pub.installmentParam.currentBank.paymentMethods, function (x) {
                return String(x.paymentMethod) === String(code);
            })[0];
            $.each($('li[data-ref=i_methodCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            var isActive = $('li[data-ref=i_methodCode][data-code=' + code + ']');
            if (isActive.length > 0) {
                isActive.find('span').addClass('active');
            }
            var row = {
                rowHeader: [],
                rowOriginAmount: [],
                rowAmountByMonth: [],
                rowAmountFinal: [],
                rowAmountFee: [],
                rowOption: []
            };
            $.each(pub.installmentParam.currentMethod.periods, function (index, period) {
                var iActive = index === 0;
                if (iActive) {
                    pub.payment.installment_month = period.month;
                }
                row.rowHeader.unshift('<td class="text-blue">' + period.month + ' tháng</td>');
                row.rowOriginAmount.unshift('<td><b>' + pub.installmentParam.originAmount + '</b></td>');
                row.rowAmountByMonth.unshift('<td><b>' + period.amountByMonth + '</b></td>');
                row.rowAmountFinal.unshift('<td><b>' + period.amountFinal + '</b></td>');
                row.rowAmountFee.unshift('<td><b>' + period.amountFee + '</b></td>');
                row.rowOption.unshift('<td>\n' +
                    '<div class="form-check">\n' +
                    '     <input class="form-check-input" type="radio" value="' + period.month + '"  ' + (iActive ? 'checked' : '') + '  id="installment' + period.month + '" name="installmentPeriod" checked="">\n' +
                    '     <label class="form-check-label" for="installment' + period.month + '">Chọn</label>\n' +
                    '     </div>\n' +
                    '</td>');
            });
            row.rowHeader.unshift('<td>Thời hạn trả góp</td>');
            row.rowOriginAmount.unshift('<td>Giá Weshop</td>');
            row.rowAmountByMonth.unshift('<td>Tiền trả hàng tháng</td>');
            row.rowAmountFinal.unshift('<td>Giá sau trả góp</td>');
            row.rowAmountFee.unshift('<td>Giá chênh lệch</td>');
            row.rowOption.unshift('<td></td>');
            var table = '<table class="table table-bordered"><tbody>';
            table += '<tr>' + row.rowHeader.join('') + '</tr>';
            table += '<tr>' + row.rowOriginAmount.join('') + '</tr>';
            table += '<tr>' + row.rowAmountByMonth.join('') + '</tr>';
            table += '<tr>' + row.rowAmountFinal.join('') + '</tr>';
            table += '<tr>' + row.rowAmountFee.join('') + '</tr>';
            table += '<tr>' + row.rowOption.join('') + '</tr>';
            table += '</tbody></table>';
            $('div#installmentPeriods').html(table);

        },
        courierChange: function (courier) {
            pub.set('service_code', courier.service_code);
            pub.set('courier_name', courier.courier_name + courier.service_name);
            pub.set('courier_logo', courier.courier_logo);
            pub.set('courier_fee', courier.shipping_fee);
            pub.set('courier_delivery_time', courier.min_delivery_time + '-' + courier.max_delivery_time);
            pub.set('courier_detail', courier);
            var additionalFees = pub.get('additionalFees', {});
            additionalFees.international_shipping_fee = courier.shipping_fee;
            pub.set('additionalFees', additionalFees);
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
            } else if (pub.payment.type === 'installment') {
                pub.calculateInstallment();
            } else {
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
            }


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
        activePaymentStep: function (step) {
            current_step = step;
            showStep(step);
        },
        process: function () {
            var $termAgree = $('input#termCheckout').is(':checked');
            if (!$termAgree) {
                ws.notifyError('Bạn phải đồng ý với điều khoản weshop');
                return;
            }
            processPaymment();
        },
        topUp: function () {
            pub.payment.total_order_amount = $('input[name=amount_topup]').val();
            if (pub.payment.total_order_amount < 100000) {
                ws.notifyError('Bạn cần phải nạp trên 100.000');
                return;
            }
            var checkArr = $('#termCheckout:checked').val();
            if (!checkArr) {
                ws.notifyError('Bạn chưa đồng ý với điều khoản và điều kiện giao dịch của weshop');
                return;
            }
            if (!pub.payment.payment_method || !pub.payment.payment_provider || !pub.payment.payment_bank_code) {
                ws.notifyError('Vui lòng chọn phương thức thanh toán!');
                return;
            }
            ws.loading(true);
            ws.ajax('/my-wallet/topup.html', {
                dataType: 'json',
                type: 'post',
                data: {payment: pub.payment},
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        ws.redirect(response.data.checkoutUrl);
                    } else {
                        ws.notifyError(response.message);
                    }

                }
            }, true);
        },
        installment: function () {
            var $termInstallment = $('input#termInstallment').is(':checked');
            if (!$termInstallment) {
                ws.notifyError('Bạn phải đồng ý với điều khoản trả góp weshop');
                return;
            }
            processPaymment();
        },
        filterShippingAddress: function (isSafe = true) {
            var form = $('form.shipping-form');
            if (!form.length > 0) {
                return false;
            }
            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }

            // var formDataArray = form.serializeArray();
            // var values = formDataArray.reduce(function (result, item) {
            //     result[item.name] = item.value;
            //     return result;
            // }, []);
            // console.log(values);
            // pub.shipping = values;

            pub.shipping.buyer_name = $('#shippingform-buyer_name').val();
            pub.shipping.buyer_phone = $('#shippingform-buyer_phone').val();
            pub.shipping.buyer_email = $('#shippingform-buyer_email').val();
            pub.shipping.buyer_province_id = $('#shippingform-buyer_province_id').val();
            pub.shipping.buyer_district_id = $('#shippingform-buyer_district_id').val();
            pub.shipping.buyer_address = $('#shippingform-buyer_address').val();
            pub.shipping.receiver_name = $('#shippingform-receiver_name').val();
            pub.shipping.receiver_phone = $('#shippingform-receiver_phone').val();
            pub.shipping.receiver_email = $('#shippingform-receiver_email').val();
            pub.shipping.receiver_province_id = $('#shippingform-receiver_province_id').val();
            pub.shipping.receiver_district_id = $('#shippingform-receiver_district_id').val();
            pub.shipping.receiver_address = $('#shippingform-receiver_address').val();
            pub.shipping.note_by_customer = $('#shippingform-note_by_customer').val();
            pub.shipping.save_my_address = $('#shippingform-save_my_address:checked').val();
            pub.shipping.receiver_address_id = $('#shippingform-receiver_address_id').val();
            pub.shipping.other_receiver = $('#shippingform-other_receiver').is(':checked');
            if (isSafe) {
                if (!pub.shipping.buyer_name || !pub.shipping.buyer_phone || !pub.shipping.buyer_email || !pub.shipping.buyer_province_id || !pub.shipping.buyer_district_id) {
                    ws.notifyError('Vui lòng nhập đầy đủ thông tin người mua');
                    return false;
                }
                if (pub.shipping.other_receiver && (!pub.shipping.receiver_name || !pub.shipping.receiver_phone || !pub.shipping.receiver_email || !pub.shipping.receiver_province_id || !pub.shipping.receiver_district_id)) {
                    ws.notifyError('Vui lòng nhập đầy đủ thông tin người nhận');
                    return false;
                }
            }
            return true;
        },
        saveShippingAddress: function () {
            if (pub.filterShippingAddress(true)) {
                return false
            }
            console.log(pub.shipping);
        }
    };
    var checkPayment = function (merchant, code, token, loading = false) {
        var $isSuccess = false;
        ws.ajax('/payment/' + merchant + '/check-recursive', {
            dataType: 'json',
            type: 'post',
            data: {code: code, token: token},
            success: function (response) {
                $isSuccess = response;
            }
        }, loading);
        return $isSuccess;
    };
    var processPaymment = function () {
        if (!pub.filterShippingAddress()) {
            return;
        }
        ws.ajax('/payment/payment/process', {
            dataType: 'json',
            type: 'post',
            data: {payment: pub.payment, shipping: pub.shipping},
            success: function (response) {

                if (response.success) {
                    var data = $.extend({}, {
                        success: false,
                        message: '',
                        merchant: undefined,
                        paymentTransaction: null,
                        redirectType: 'normal',
                        redirectMethod: 'get',
                        token: null,
                        status: null,
                        checkoutUrl: null,
                        returnUrl: null,
                        cancelUrl: null
                    }, response.data || {});
                    var redirectType = data.redirectType.toUpperCase();
                    var redirectMethod = data.redirectMethod.toUpperCase() || 'GET';
                    if (redirectType === 'POPUP') {
                        if (redirectMethod === 'WALLET') {
                            var $otp = $('#otp-confirm');
                            $otp.modal('show').find('#modalContent').load(data.checkoutUrl);
                        } else if (redirectMethod === 'QRCODE') {
                            var $qr = $('#qr-pay');
                            var base64src = function (src) {
                                return 'data:image/png;base64,' + src;
                            };
                            $qr.on('shown.bs.modal', function (e) {
                                e.preventDefault();
                                var runTime = setInterval(function () {
                                    var success = checkPayment(data.merchant, data.paymentTransaction, data.token);
                                    if (success) {
                                        clearInterval(runTime);
                                        ws.redirect(data.returnUrl);
                                    }
                                }, 1000);
                                $qr.on('hidden.bs.modal', function (e) {
                                    e.preventDefault();
                                    clearInterval(runTime);
                                    //ws.redirect(data.cancelUrl);
                                });
                            });

                            $qr.modal('show').find('#qrCodeImg').attr('src', base64src(data.checkoutUrl));

                        }
                    } else if (data.paymentTransaction) {
                        $('span#transactionCode').html(data.paymentTransaction);
                        $('div#checkout-success').modal('show');
                        ws.initEventHandler('checkoutSuccess', 'nextPayment', 'click', 'button#next-payment', function (e) {
                            if (redirectMethod === 'POST') {
                                $(data.checkoutUrl).appendTo('body').submit();
                            } else {
                                ws.redirect(data.checkoutUrl);
                            }
                        });
                        redirectPaymentGateway(data, 1000);
                    }
                } else {
                    ws.notifyError(response.message);
                }

            }
        }, true)
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
        var billingBox = $('#billingBox');
        var discountBox = billingBox.find('li#discountPrice');
        discountBox.css('display', 'none');
        if ($response.details.length > 0 && $response.discount > 0) {
            pub.payment.discount_detail = $response.details;
            pub.payment.total_discount_amount = $response.discount;
            var totalAmount = pub.payment.total_order_amount;
            $.each(pub.payment.additionalFees, function (ikey, value) {
                totalAmount += Number(value);
            });
            pub.payment.totalAmount = totalAmount;
            pub.payment.totalAmountDisplay = pub.payment.totalAmount - pub.payment.total_discount_amount;
            discountBox.css('display', 'flex');
            discountBox.find('div.right').html('- ' + ws.showMoney(pub.payment.total_discount_amount));
            billingBox.find('li#finalPrice').find('div.right').html(ws.showMoney(pub.payment.totalAmountDisplay));
            billingBox.find('li[rel="detail"]').remove();
            billingBox.prepend(initPromotionView(pub.payment.discount_detail));

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
                text += '<i class="la la-times text-danger remove"  title="Remove ' + item.code + '" onclick="ws.payment.changeCouponCode(\'' + item.code + '\')"></i>';
                $('#discountInputCoupon').css('display', 'none');
            }
            text += item.code + '<i class="la la-question-circle code-info" data-toggle="tooltip" data-placement="top" title="' + item.message + '" data-original-title="' + item.message + '"></i></div></div>';
            text += '<div class="right"> - ' + ws.showMoney(item.value) + '</div>';
            text += '</li>';
        });
        return text;
    };
    var initInstallmentBankView = function (banks) {
        pub.installmentParam.banks = banks;
        console.log(banks);
        var htmlBank = [];
        $.each(banks, function (index, bank) {
            var iActive = index === 0;
            if (iActive) {
                pub.payment.installment_bank = bank.bankCode;
                pub.installmentBankChange(bank.bankCode);
            }
            var $ele = '<li data-ref="i_bankCode" data-code="' + bank.bankCode + '"  onclick="ws.payment.installmentBankChange(\'' + bank.bankCode + '\')"><span class="' + (iActive ? "active" : "") + '"><img src="' + bank.bankIcon + '" alt="' + bank.bankName + '" title="' + bank.bankName + '"/></span></li>';
            htmlBank.push($ele)
        });
        $('ul#installmentBanks').html(htmlBank.join(''));
        console.log(banks)
    };
    var redirectPaymentGateway = function (rs, $timeOut) {
        var runTime = setInterval(function () {
            var second = parseInt($("#countdown_payment").text());
            if (second > 0) {
                $("#countdown_payment").text(second - 1);
            } else {
                var redirectMethod = rs.redirectMethod.toUpperCase() || 'GET';
                if (redirectMethod === 'POST') {
                    $(rs.checkoutUrl).appendTo('body').submit();
                    clearInterval(runTime);
                } else {
                    ws.redirect(rs.checkoutUrl);
                    clearInterval(runTime);
                }
            }
        }, $timeOut);
    };
    var showStep = function ($step) {
        $step = $step - 1;
        $('.checkout-step li').each(function (i, li) {
            var $li = $(li);
            $li.removeClass('active');

            $($li.data('href')).css('display', 'none');
            if ($step === i) {
                $li.addClass('active');
                $($li.data('href')).css('display', 'block');

            }
        });
    };
    var showAdditinalFee = function (fee) {
        var $localAmount = 0;
        var $lable = 'Unknonw fee';
        if (fee.length > 0) {
            for (var i = 0; i < fee.length; i++) {
                $localAmount += fee[i].local_amount
            }
        }
        return {label: $lable, amountOrigin: $localAmount, amountLocalized: ws.showMoney($localAmount)}
    };
    var initCourierView = function (couriers) {

    };
    var initAdditionalFeeView = function () {

    };
    var initPaymentPopup = function ($res) {

    };

    return pub;
})(jQuery);