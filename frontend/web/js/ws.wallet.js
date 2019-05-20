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
                openComfirmPassword();
            }
            return pub.data;
        },
        validateOTP: function () {

        },
        reSentOtp: function () {

        },
        confirmPassword: function () {

        },
    };
    return pub;
    var openConfirmPassword = function () {
        
    }
})(jQuery);