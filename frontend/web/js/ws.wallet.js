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
            console.log(pub.data);
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
        refreshOtp: function () {

        },
        confirmPassword: function () {

        },
        otpExpireCoolDown: function (target) {
             target = $(target);
            var interval = setInterval(function () {
                var uri = target.data('redirect-uri');
                var time = parseInt(target.data('time-expired'));
                var left = time - Math.floor((new Date()) / 1000);
                console.log(left);
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