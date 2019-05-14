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
        methods: [],
        init: function (options) {
            pub.payment = $.extend({}, defaults, options || {});
            if (pub.payment.page !== 4) {
                setTimeout(function () {
                    pub.checkPromotion();
                }, 300)
            }
        },
        selectMethod: function (providerId, methodId, bankCode) {
            console.log('selected providerId:' + providerId + ' methodId:'+methodId +' bankCode:' +bankCode);
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
        methodChange: function(isNew) {
            isNew = isNew || false;
        },
        checkPromotion: function () {

        }
    };
    return pub;
})(jQuery);