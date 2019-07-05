ws.wallet = (function ($) {
    var setting = {
        is_guest: true,
        current_balance: 0,
        freeze_balance: 0,
        usable_balance: 0,
        otp_receive_type: 1,
        receive_types: []
    };
    var pub = {
        data: {},
        init: function (options) {
            pub.data = $.extend({}, setting, options || {});
            //console.log(pub.data);
            ws.initEventHandler('otpVerifyMethod', 'change', 'change', 'input[type=radio][name=otpVerifyMethod]', function (e) {
                e.preventDefault();
                pub.data.otp_receive_type = Number($(this).val());
                console.log($(this).val());
            });
        },
        getInfo: function () {
            var data = pub.data;
            if (data.is_guest) {
                // openConfirmPassword();
            }
            return pub.data;
        },
        refreshCaptcha: function () {
            $("img[id$='-captcha-image']").trigger('click');
        },
        refreshOtp: function (form) {
            var otpVerifyForm = $(form);
            ws.ajax('/payment/wallet/refresh-otp', {
                type: 'POST',
                data: otpVerifyForm.serialize(),
                success: function (res) {
                    $msg = $('p.message-otp');
                    var $oldText = $msg.html();
                    $('p.message-otp').html(res.message);
                    if (!res.success) {
                        var timeOut = 5;
                        var runTime = setInterval(function () {
                            timeOut -= 1;
                            if (timeOut === 0) {
                                $msg.html($oldText);
                                $oldText = '';
                                pub.otpExpireCoolDown('span.otp-expired-cooldown');
                                clearInterval(runTime);
                            }
                        }, 1000);
                    }
                    pub.otpExpireCoolDown('span.otp-expired-cooldown');
                }
            })
        },
        submitForm(form) {
            if (form.find('.has-error').length) {
                return false;
            }
            ws.ajax(form.attr('action'), {
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        var data = response.data;
                        $('body').find('form.otp-submit-form').remove();
                        var newForm = $('<form/>', {
                            action: data.returnUrl,
                            method: 'get',
                            'class': 'otp-submit-form',
                            style: 'display:none',
                            'data-pjax': ''
                        }).appendTo('body');
                        newForm.append($('<input/>').attr({type: 'hidden', name: 'status', value: response.code}));
                        newForm.append($('<input/>').attr({type: 'hidden', name: 'token', value: data.token}));
                        newForm.append($('<input/>').attr({
                            type: 'hidden',
                            name: 'order_code',
                            value: data.order_code
                        }));
                        newForm.append($('<input/>').attr({type: 'hidden', name: 'time', value: data.time}));
                        newForm.submit();
                    }
                }
            });
            return false;
        },
        confirmPassword: function () {

        },
        otpExpireCoolDown: function (target) {
            target = $(target);
            var interval = setInterval(function () {
                var uri = target.data('redirect-uri');
                var time = parseInt(target.data('time-expired'));
                var left = time - Math.floor((new Date()) / 1000);
                if (left < 0) {
                    left = 0;
                }
                if (left === 0) {
                    clearInterval(interval);
                    console.log('VLV: typeof uri:' + typeof uri + ', uri:' + uri);
                    window.location.assign(uri);
                }
                var m = Math.floor(left / 60);
                left -= m * 60;
                var s = left;
                target.html(' ' + m + 'm ' + s + 's ');
            }, 1000);
        }
    };
    return pub;
    var openConfirmPassword = function () {

    }
})(jQuery);