ws.payment = (function ($) {
    var defaults = {
        page: undefined,
        orders: [],
        use_xu: 0,
        bulk_point: 0,
        coupon_code: undefined,
        discount_detail: [],
        total_discount_amount: 0,
        currency: undefined,
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
        installmentMethods: [],
        init: function (options) {
            pub.payment = $.extend({}, defaults, options || {});
            if (pub.payment.page !== 4) {
                setTimeout(function () {
                    pub.payment.checkPromotion($);
                }, 300)
            }
        },
        selectMethod: function (providerId, methodId, bankCode) {

        },
        checkPromotion: function () {

        }
    };
    return pub;
})(jQuery);