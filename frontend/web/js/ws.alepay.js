ws.alepay = (function ($) {
    var defaultConfig = {
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
    var pub = {
        options: {},
        init: function () {
            pub.calculate();
            ws.initEventHandler('calculate', 'periodChange', 'change', 'input[name=installmentPeriod]', function (e) {
                ws.payment.set('installment_month', $(this).val());
            });
        },
        calculate: function () {
            ws.ajax('/payment/' + pub.payment.payment_provider + '/calc', {
                dataType: 'json',
                type: 'post',
                data: pub.payment,
                success: function (response) {
                    var data = response.data;
                    var banks = data.methods || [];
                    var promotion = data.promotion || undefined;
                    pub.originAmount = data.origin || (0 + pub.payment.currency);
                    pub.initBankView(banks);
                    if (promotion !== undefined) {
                        // ws.payment.updatePaymentByPromotion(promotion)
                    }
                }
            }, true);
        },
        bankChange: function (code) {
            console.log('selected bank :' + code);
            ws.payment.installment_bank = code;
            pub.currentBank = $.grep(pub.installment.banks, function (x) {
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
            $.each(pub.options.currentBank.paymentMethods, function (index, method) {
                var iActive = index === 0;
                if (iActive) {
                    ws.payment.set('installment_method', method.paymentMethod);
                    pub.methodChange(method.paymentMethod);
                }
                var $ele = '<li data-ref="i_methodCode" data-code="' + method.paymentMethod + '"  onclick="ws.alepay.methodChange(\'' + method.paymentMethod + '\')"><span class="' + (iActive ? "active" : "") + '"><img src="' + method.methodIcon + '" alt="' + method.paymentMethod + '" title="' + method.paymentMethod + '"/></span></li>';
                htmlMethod.push($ele)
            });
            $('ul#installmentMethods').html(htmlMethod.join(''));
        },
        methodChange(code) {
            console.log('selected method :' + code);
            pub.payment.set('installment_method', code);
            pub.options.currentMethod = $.grep(pub.options.currentBank.paymentMethods, function (x) {
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
            $.each(pub.options.currentMethod.periods, function (index, period) {
                var iActive = index === 0;
                if (iActive) {
                    pub.payment.installment_month = period.month;
                }
                row.rowHeader.unshift('<td class="text-blue">' + period.month + ' tháng</td>');
                row.rowOriginAmount.unshift('<td><b>' + pub.options.originAmount + '</b></td>');
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
        initBankView: function (banks) {
            pub.banks = banks;
            console.log(banks);
            var htmlBank = [];
            $.each(banks, function (index, bank) {
                var iActive = index === 0;
                if (iActive) {
                    ws.payment.set('installment_bank', bank.bankCode);
                    pub.bankChange(bank.bankCode);
                }
                var $ele = '<li data-ref="i_bankCode" data-code="' + bank.bankCode + '"  onclick="ws.alepay.bankChange(\'' + bank.bankCode + '\')"><span class="' + (iActive ? "active" : "") + '"><img src="' + bank.bankIcon + '" alt="' + bank.bankName + '" title="' + bank.bankName + '"/></span></li>';
                htmlBank.push($ele)
            });
            $('ul#installmentBanks').html(htmlBank.join(''));
            console.log(banks)
        }
    };
    return pub;
})(jQuery);