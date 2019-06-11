ws.alepay = (function ($) {
    var installmentParam = {
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
    };

    return {
        init:function () {

        }
    };
})(jQuery);