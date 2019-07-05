ws.payment = (function ($) {
    var defaults = {
        page: undefined,
        uuid: ws.getFingerprint(),
        type: undefined,
        orders: [],
        currency: 'VND',
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
    var orderCouriers = [];
    var pub = {
        payment: {},
        options: {},
        methods: [],
        shipping: {},
        installmentParam: {
            calculator: undefined,
            originAmount: 0,
            banks: [],
            currentBank: {
                name: undefined,
                code: undefined,
                icon: undefined,
                method: 'VISA',
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
                var button = $(this);
                var key = button.data('key');
                var $cardOrder = $('div.card-order[data-key=' + key + ']');
                var $input = button.parents('div.discount-input').find('input[name="couponCode"]');
                if ($input.length > 0 && $input.val() !== '') {
                    var orders = pub.get('orders');
                    var order = orders[key];
                    order.couponCode = $input.val();
                    order.discountDetail = [];
                    order.discountAmount = 0;
                    orders[key] = order;
                    pub.set('orders', orders);
                    pub.checkPromotion();
                }
            });

            setTimeout(function () {
                pub.calculatorShipping();
                //console.log(pub.payment);
            }, 1000);

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
            $('#shippingform-buyer_phone').keyup(function () {
                var isCheckOut = ws.payment.get('page') === 'CHECKOUT';
                if (!isCheckOut) {
                    return;
                }
                ws.shippingCollection('buyer', ws.payment.get('type'), Object.keys(ws.payment.get('orders')))
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
            $('#shippingform-buyer_province_id').change(function () {
                var txt = '';
                district_data.filter(function (d) {
                    if (d.province_id == $('#shippingform-buyer_province_id').val()) {
                        txt += '<option value="' + d.id + '">' + d.name + '</option>';
                    }
                });
                $('#shippingform-buyer_district_id').html(txt);
                $('#shippingform-buyer_district_id').trigger('change');
                // ws.payment.calculatorShipping();
            });
            $('#shippingform-receiver_province_id').change(function () {
                var txt = '';
                district_data.filter(function (d) {
                    if (d.province_id == $('#shippingform-receiver_province_id').val()) {
                        txt += '<option value="' + d.id + '">' + d.name + '</option>';
                    }
                });
                $('#shippingform-receiver_district_id').html(txt);
                $('#shippingform-receiver_district_id').trigger('change');
                // ws.payment.calculatorShipping();
            });
            $('#shippingform-buyer_district_id').change(function () {
                if (store_id === 7) {
                    var district = $('#shippingform-buyer_district_id').val();
                    var zipcode = zipcode_data.filter(z => z.district_id === district);
                    if (zipcode && zipcode.length > 0) {
                        $('#shippingform-buyer_post_code').val(zipcode[0].zip_code);
                        ws.payment.calculatorShipping();
                    } else {
                        ws.notifyInfo('Cannot find zipcode from your district ');
                        $('#shippingform-buyer_post_code').val('');
                    }
                } else {
                    ws.payment.calculatorShipping();
                }
            });
            $('#shippingform-receiver_district_id').change(function () {
                if (store_id === 7) {
                    var district = $('#shippingform-receiver_district_id').val();
                    var zipcode = zipcode_data.filter(z => z.district_id === district);
                    if (zipcode && zipcode.length > 0) {
                        $('#shippingform-receiver_post_code').val(zipcode[0].zip_code);
                        ws.payment.calculatorShipping();
                    } else {
                        ws.notifyInfo('Cannot find zipcode from your district ');
                        $('#shippingform-receiver_post_code').val('');
                    }
                } else {
                    ws.payment.calculatorShipping();
                }
            });
            $('#shippingform-buyer_post_code').keyup(function () {
                var txt = '';
                var count = 0;
                var zipcode = $('#shippingform-buyer_post_code').val();
                zipcode_data.filter(function (z) {
                    if (count < 20 && (z.zip_code.indexOf(zipcode) > -1 || !zipcode)) {
                        count++;
                        txt += "<option value='" + z.zip_code + "'>" + z.label + "</option>";
                    }
                });
                $('#buyer_post_code_list').html(txt);
            });
            $('#shippingform-receiver_post_code').keyup(function () {
                var txt = '';
                var count = 0;
                var zipcode = $('#shippingform-receiver_post_code').val();
                zipcode_data.filter(function (z) {
                    if (count < 20 && (z.zip_code.indexOf(zipcode) > -1 || !zipcode)) {
                        count++;
                        txt += "<option value='" + z.zip_code + "'>" + z.label + "</option>";
                    }
                });
                $('#receiver_post_code_list').html(txt);
            });

            $('#shippingform-buyer_post_code').change(function () {
                var zipcode = zipcode_data.filter(z => z.zip_code === $('#shippingform-buyer_post_code').val());
                if (zipcode && zipcode.length > 0) {
                    $('#shippingform-buyer_province_id').val(zipcode[0].province_id).trigger('change');
                    $('#shippingform-buyer_district_id').val(zipcode[0].district_id).trigger('change');
                } else {
                    ws.notifyInfo('Zip code ' + $('#shippingform-buyer_post_code').val() + ' not support! Please change zip code');
                    $('#shippingform-buyer_post_code').val('');
                }
            });

            $('#shippingform-receiver_post_code').change(function () {
                var zipcode = zipcode_data.filter(z => z.zip_code === $('#shippingform-receiver_post_code').val());
                if (zipcode && zipcode.length > 0) {
                    $('#shippingform-receiver_province_id').val(zipcode[0].province_id).trigger('change');
                    $('#shippingform-receiver_district_id').val(zipcode[0].district_id).trigger('change');
                    $('#shippingform-receiver_post_code').val(zipcode[0].zip_code);
                } else {
                    ws.notifyInfo('Zip code ' + $('#shippingform-receiver_post_code').val() + ' not support! Please change zip code');
                    $('#shippingform-receiver_post_code').val('');
                }
            });
        },
        set: function (name, value) {
            pub.payment[name] = value;
        },
        get: function (name, defaultValue) {
            return pub.payment[name] || defaultValue;
        },
        selectMethod: function (providerId, methodId, bankCode, checkRequire = false) {
            //console.log('selected providerId:' + providerId + ' methodId:' + methodId + ' bankCode:' + bankCode);
            pub.payment.payment_provider = providerId;
            pub.payment.payment_method = methodId;
            pub.payment.payment_bank_code = bankCode;
            if (bankCode === 'ALEPAY') {
                pub.calculateInstallment(false);
            }
            // if (checkRequire === true) {
            //     pub.checkRequireField();
            // }
            $.each($('li[rel=s_bankCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            var activeMethod = $('#bank_code_' + bankCode + '_' + methodId);
            if (activeMethod.length > 0) {
                activeMethod.find('span').addClass('active');
            }
            //pub.checkPromotion();
        },
        checkRequireField: function () {
            // ws.ajax('/payment/' + pub.payment.payment_provider + '/check-field', {
            //     dataType: 'json',
            //     type: 'post',
            //     data: pub.payment,
            //     success: function (response) {
            //         console.log(response);
            //     }
            // });
        },
        registerMethods: function ($methods) {
            pub.methods = $methods;
            //console.log('register ' + pub.methods.length + ' methods');
        },
        calculatorShipping: function () {
            if (!pub.filterShippingAddress(false)) {
                return;
            }

            if (Number(pub.shipping.enable_buyer) === 1 && (!pub.shipping.buyer_province_id || !pub.shipping.buyer_district_id)) {
                return false;
            } else if (pub.shipping.other_receiver === true && Number(pub.shipping.enable_receiver) === 1) {
                if (pub.shipping.other_receiver === true && (!pub.shipping.receiver_province_id || !pub.shipping.receiver_district_id)) {
                    return false;
                }
            }
            ws.ajax('/payment/courier/calculator', {
                dataType: 'json',
                type: 'post',
                data: {payment: pub.payment, shipping: pub.shipping},
                success: function (response) {
                    //console.log(response);
                    if (response.success) {
                        var orders = response.data;
                        orderCouriers = orders;
                        $.each(orders, function (key, res) {
                            initCourierView(key, res);
                        });

                        pub.checkPromotion();
                    } else {
                        ws.notifyError(response.message);
                    }
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
                    pub.installmentParam.calculator = data.calculator;
                    pub.installmentParam.originAmount = data.origin || ws.showMoney(0);
                    initInstallmentBankView(banks);
                    ws.initEventHandler('calculateInstallment', 'periodChange', 'change', 'input[name=installmentPeriod]', function (e) {
                        pub.payment.installment_month = $(this).val();
                    });
                }
            }, true);
        },
        installmentBankChange: function (code) {
            //console.log('selected bank :' + code);
            pub.payment.installment_bank = code;
            pub.payment.installment_method = 'VISA';
            var currentBank = $.grep(pub.installmentParam.banks, function (x) {
                return String(x.code) === String(code);
            })[0];
            $.each($('li[data-ref=i_bankCode]'), function () {
                $(this).find('span').removeClass('active');
            });
            var isActive = $('li[data-ref=i_bankCode][data-code=' + code + ']');
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

            $.each(currentBank.periods, function (index, period) {
                var iActive = index === 0;
                if (iActive) {
                    pub.payment.installment_month = period.month;
                }
                row.rowHeader.unshift('<td class="text-blue">' + period.month + ' tháng</td>');
                row.rowOriginAmount.unshift('<td><b>' + pub.installmentParam.originAmount + '</b></td>');
                row.rowAmountByMonth.unshift('<td><b>' + period.amountByMonth + '</b></td>');
                row.rowAmountFinal.unshift('<td><b>' + ws.showMoney(period.amountFinal) + '</b></td>');
                row.rowAmountFee.unshift('<td><b>' + ws.showMoney(period.amountFee) + '</b></td>');
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
        courierChange: function (key, service_code) {
            var fee = {
                amount: 0,
                currency: pub.get('currency'),
                discount_amount: 0,
                label: 'International Shipping Fee',
                local_amount: 0,
                name: 'international_shipping_fee',
                type: 'local'
            };

            var $cardOrder = $('div.card-order[data-key=' + key + ']');

            var couriers = orderCouriers[key];

            var courier = $.grep(couriers.couriers, function (x) {
                return x.service_code === service_code
            })[0];


            // var text = courier.courier_name + ' ' + courier.service_name + ' (' + courier.min_delivery_time + '-' + courier.max_delivery_time + ' ' + ws.t('days') + ' )';
            // var courierDropDown = $cardOrder.find('div.courier-dropdown');
            // courierDropDown.find('button#courierDropdownButton').find('.courier-name').html(text);

            var orders = pub.get('orders');
            var order = orders[key];
            var shippingFee = ws.roundNumber(courier.total_fee);
            var additionalFees = order.additionalFees;
            if (fee.name in additionalFees) {
                delete additionalFees[fee.name];
            }
            additionalFees[fee.name] = [];
            fee.amount = shippingFee;
            fee.local_amount = shippingFee;
            additionalFees[fee.name].push(fee);
            order.additionalFees = additionalFees;
            order.courierDetail = courier;
            initRowTableFee($cardOrder, fee);
            orders[key] = order;
            pub.set('orders', orders);
            var tableFee = $cardOrder.find('table.table-fee');

            var courierRow = tableFee.find('tr.courier');
            var text = courier.min_delivery_time + '-' + courier.max_delivery_time + ' ' + ws.t('days');
            courierRow.find('td.text-right').html(text);

            var orderAmount = getTotalOrderAmount(order);
            var totalFinal = tableFee.find('tr.final-amount').find('.value');
            totalFinal.html(ws.showMoney(orderAmount));
            getTotalAmountDisplay();

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
            if (pub.payment.orders.length === 0) {
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
                        updatePaymentByPromotion(response.data)
                    }
                })
            }
        },
        removeCouponCode: function (key) {
            var orders = pub.get('orders');
            var order = orders[key];
            order.couponCode = null;
            order.discountDetail = [];
            order.discountAmount = 0;
            orders[key] = order;
            pub.set('orders', orders);
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
            // var $termAgree = $('input#termCheckout').is(':checked');
            // if (!$termAgree) {
            //     ws.notifyError('Bạn phải đồng ý với điều khoản weshop');
            //     return;
            // }
            processPaymment();
        },
        billing: function () {
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
            pub.shipping.customer_id = $('#shippingform-customer_id').val();
            pub.shipping.enable_buyer = $('#shippingform-enable_buyer').val();
            pub.shipping.buyer_address_id = $('[name="ShippingForm[buyer_address_id]"]').val();
            pub.shipping.buyer_name = $('#shippingform-buyer_name').val();
            pub.shipping.buyer_phone = $('#shippingform-buyer_phone').val();
            pub.shipping.buyer_email = $('#shippingform-buyer_email').val();
            pub.shipping.buyer_province_id = $('#shippingform-buyer_province_id').val();
            pub.shipping.buyer_district_id = $('#shippingform-buyer_district_id').val();
            pub.shipping.buyer_address = $('#shippingform-buyer_address').val();
            pub.shipping.buyer_post_code = $('#shippingform-buyer_post_code').val();

            pub.shipping.enable_receiver = $('#shippingform-enable_receiver').val();
            pub.shipping.receiver_address_id = $('[name="ShippingForm[receiver_address_id]:checked').val();
            pub.shipping.receiver_name = $('#shippingform-receiver_name').val();
            pub.shipping.receiver_phone = $('#shippingform-receiver_phone').val();
            pub.shipping.receiver_province_id = $('#shippingform-receiver_province_id').val();
            pub.shipping.receiver_district_id = $('#shippingform-receiver_district_id').val();
            pub.shipping.receiver_address = $('#shippingform-receiver_address').val();
            pub.shipping.note_by_customer = $('#shippingform-note_by_customer').val();
            pub.shipping.save_my_address = $('#shippingform-save_my_address:checked').val();
            pub.shipping.receiver_post_code = $('#shippingform-receiver_post_code').val();
            pub.shipping.other_receiver = $('#shippingform-other_receiver').is(':checked');
            // case 1 //
            if (isSafe) {
                if (Number(pub.shipping.enable_buyer) === 1 && (!pub.shipping.buyer_name || !pub.shipping.buyer_phone || !pub.shipping.buyer_email || !pub.shipping.buyer_province_id || !pub.shipping.buyer_district_id)) {
                    ws.notifyError('Vui lòng nhập đầy đủ thông tin người mua');
                    return false;
                }

                if (pub.shipping.other_receiver === true && Number(pub.shipping.enable_receiver) === 1) {
                    if (pub.shipping.other_receiver === true && (!pub.shipping.receiver_name || !pub.shipping.receiver_phone || !pub.shipping.receiver_province_id || !pub.shipping.receiver_district_id)) {
                        ws.notifyError('Vui lòng nhập đầy đủ thông tin người nhận');
                        return false;
                    }
                }
            }

            return true;
        },
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
        var handleUrl = '/payment/payment/process';
        var data = {payment: pub.payment, shipping: pub.shipping};
        ws.ajax(handleUrl, {
            dataType: 'json',
            type: 'post',
            data: data,
            success: function (response) {

                if (response.success) {
                    var data = $.extend({}, {
                        success: false,
                        message: '',
                        merchant: undefined,
                        paymentTransaction: null,
                        orderCodes: null,
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
                    var $otherMethod = $('#otherMethods');
                    if ($otherMethod.length && $otherMethod.hasClass('show')) {
                        $otherMethod.modal('hide');
                    }
                    if (data.cancelUrl !== null) {
                        var relativeUrl = ws.relativeUrl(data.cancelUrl);
                        history.replaceState({url: relativeUrl}, window.document.title, relativeUrl);
                    }
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
                        var invoiceHide = $('p.invoice-hide');
                        invoiceHide.find('span').html(data.orderCodes);
                        invoiceHide.css('display', 'block');
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
                    var url = response.data || null;
                    if (url !== null && typeof url === 'string') {
                        setTimeout(function () {
                            ws.redirect(url);
                        }, 1000);
                    }
                }

            }
        }, true)
    };
    var updatePaymentByPromotion = function ($response) {

        var orders = pub.get('orders');
        $.each($response, function (key, data) {
            var order = orders[key];

            var $cardOrder = $('div.card-order[data-key=' + key + ']');
            var $discountBox = $cardOrder.find('table.table-fee').find('tr.discount-detail');
            $discountBox.find('.discount-input').css('display', 'flex');
            $discountBox.find('.coupon-code').html('');
            $discountBox.find('.discount-amount').css('display', 'none');
            var $errorDiscount = $discountBox.find('td.value').find('span#discountErrors');

            if (!data.success || order.couponCode in data.errors) {
                var error = data.errors[order.couponCode];
                $errorDiscount.css('display', 'block');
                $errorDiscount.html(error);
            }

            if (data.discount > 0 && data.details.length > 0) {
                order.discountAmount = data.discount;
                order.discountDetail = data.details;
                $discountBox.find('.discount-input').css('display', 'none');
                var detail = data.details[0];
                $discountBox.find('.discount-amount').css('display', 'block');
                $discountBox.find('.discount-amount').find('.discount-value').html('- ' + ws.showMoney(detail.value));
                var info = detail.code + '<i class="la la-question-circle code-info"\n' +
                    'data-toggle="tooltip"\n' +
                    'data-placement="top"\n' +
                    'title="' + detail.message + '"\n' +
                    'data-original-title="\' + detail.message + \'"></i>'
                $discountBox.find('.coupon-code').html(info);

            }
            var tableFee = $cardOrder.find('table.table-fee');
            var orderAmount = getTotalOrderAmount(order);
            var totalFinal = tableFee.find('tr.final-amount').find('.value');
            totalFinal.html(ws.showMoney(orderAmount));
        });

        getTotalAmountDisplay();
    };
    var initInstallmentBankView = function (banks) {
        pub.installmentParam.banks = banks;
        //console.log(banks);
        var htmlBank = [];
        $.each(banks, function (index, bank) {
            var iActive = index === 0;
            if (iActive) {
                pub.payment.installment_bank = bank.code;
                pub.payment.installment_method = 'VISA';
                pub.installmentBankChange(bank.code);
            }
            var $ele = '<li data-ref="i_bankCode" data-code="' + bank.code + '"  onclick="ws.payment.installmentBankChange(\'' + bank.code + '\')"><span class="' + (iActive ? "active" : "") + '"><img src="' + bank.icon + '" alt="' + bank.name + '" title="' + bank.name + '"/></span></li>';
            htmlBank.push($ele)
        });
        $('ul#installmentBanks').html(htmlBank.join(''));
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

    var initCourierView = function (key, response) {
        var $cardOrder = $('div.card-order[data-key=' + key + ']');
        // init text
        var couriers = response.couriers;
        var courierDropDown = $cardOrder.find('div.courier-dropdown');
        if (response.success && typeof couriers !== 'string' && couriers.length) {
            if (couriers.length > 0) {
                var menu = $('<div/>').attr({
                    id: 'courierDropdownMenu',
                    class: 'dropdown-menu',
                    'aria-labelledby': 'courierDropdownButton'
                });
                $.each(couriers, function (i, courier) {
                    var text = courier.courier_name + ' ' + courier.service_name + ' (' + courier.min_delivery_time + '-' + courier.max_delivery_time + ' ' + ws.t('days') + ' )';
                    var item = '<span class="dropdown-item" onclick="ws.payment.courierChange(\'' + key + '\',\'' + courier.service_code + '\')">' + text + '</span>';
                    menu.append(item);
                });
                courierDropDown.append(menu);
            }
            pub.courierChange(key, couriers[0].service_code);
        } else if (typeof couriers === 'string') {
            var tableFee = $cardOrder.find('table.table-fee');

            var courierRow = tableFee.find('tr.courier');
            courierRow.find('td.text-right').html(couriers);
            // courierDropDown.find('button#courierDropdownButton').find('.courier-name').html(couriers)
        }
    };
    var initRowTableFee = function ($cardOrder, fee) {
        var tableFee = $cardOrder.find('table.table-fee');
        var shippingRow = tableFee.find('tr[data-fee="' + fee.name + '"]');
        shippingRow.find('td.value').html(ws.showMoney(fee.local_amount));
    };
    var getTotalOrderAmount = function (order) {
        var $amount = Number(order.totalAmountLocal);
        $.each(order.additionalFees, function (name, array) {
            var totalFeeAmount = 0;
            for (var i = 0; i < array.length; i++) {
                totalFeeAmount += Number(array[i].local_amount);
            }

            $amount += totalFeeAmount;
        });
        if (order.discountAmount > 0) {

            $amount -= order.discountAmount;
        }
        return $amount
    };

    var getTotalAmountDisplay = function () {
        var orders = pub.get('orders');
        var totalAmountDisplay = 0;
        $.each(orders, function (ikey, order) {
            totalAmountDisplay += getTotalOrderAmount(order);
        });
        var btnCheckout = $('#btnCheckout');
        btnCheckout.find('span').html(ws.showMoney(totalAmountDisplay));

        return totalAmountDisplay
    };
    return pub;
})(jQuery);
