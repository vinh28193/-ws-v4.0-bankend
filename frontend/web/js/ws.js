var ws = ws || (function ($) {

    var events = {
        ajaxBeforeSend: 'ajaxBeforeSend',
        ajaxComplete: 'ajaxComplete',
    };

    var defaultOptions = {
        currency: undefined,
        priceDecimal: 0,
        pricePrecision: 0
    };

    var eventHandlers = [];
    var i18nMessages = [];
    var pub = {
        options: {},
        init: function (options) {
            pub.options = $.extend({}, defaultOptions, options || {});
            console.log(options);
        },
        // Todo loading
        loading: function (show) {
            if (show) {
                $('#loading').css('display', 'block');
            } else {
                $('#loading').css('display', 'none');
            }
        },
        ajax: function (url, $options, loading = false) {
            if (loading) {
                pub.loading(true);
            }
            // ajax('url',function(response, textStatus, xhr){ // handler when success },true); => meaning send with default setting
            if ($.isFunction($options)) {
                $options = {'success': $options};
            }

            var beforeSendHandler = $options.beforeSend;
            var beforeSend = function (jqXHR, settings) {
                if (!$options.crossDomain && yii.getCsrfParam()) {
                    jqXHR.setRequestHeader('X-CSRF-Token', yii.getCsrfToken());
                }
                if (beforeSendHandler && $.isFunction(beforeSendHandler)) {
                    beforeSendHandler(jqXHR, settings);
                }
            };
            var successHandler = $options.success;
            var success = function (response, textStatus, xhr) {
                if (successHandler && $.isFunction(successHandler)) {
                    successHandler(response, textStatus, xhr);
                }
                if (loading) {
                    pub.loading(false);
                }
                return false;
            };
            var errorHandler = $options.error;
            var error = function (xhr, textStatus, errorThrown) {
                if (errorHandler && $.isFunction(errorHandler)) {
                    errorHandler(xhr, textStatus, errorThrown);
                }
                if (loading) {
                    pub.loading(false);
                }
                return false;
            };
            //Overwriting the handler with our wrapper handler
            $options.beforeSend = beforeSend;
            $options.success = success;
            $options.error = error;
            $options.url = url;

            $.ajax($options);
        },
        countdownTime: function () {
            $('*[data-toggle=countdown-time]').each(function () {
                var days = 24 * 60 * 60, hours = 60 * 60, minutes = 60;
                var now = (new Date().getTime() / 1000);
                var timestamp = $(this).data('timestamp') || now;
                var prefixText = $(this).data('prefix') || '';
                var dayText = $(this).data('day') || 'd';
                var hourText = $(this).data('hour') || 'h';
                var minuteText = $(this).data('minute') || 'm';
                var secondText = $(this).data('second') || 's';
                var finishText = $(this).data('finish') || 'Out of time';
                timestamp = parseInt(timestamp);
                var left = Math.floor(timestamp - now);
                if (left < 0) {
                    left = 0;
                }
                if (left === 0) {
                    $(this).html(finishText);
                } else {
                    var d = Math.floor(left / days);
                    left -= d * days;
                    var h = Math.floor(left / hours);
                    left -= h * hours;
                    var m = Math.floor(left / minutes);
                    left -= m * minutes;
                    var s = left;

                    $(this).html(prefixText + ' ' + d + ' ' + dayText + ' ' + h + ' ' + hourText + ' ' + m + ' ' + minuteText + ' ' + s + ' ' + secondText);
                }
            });
        },
        goback: function () {
            history.back()
        },
        redirect: function (href) {
            document.location.href = href;
        },
        t: function (message, params = []) {
            var hash = message;
            if (typeof (i18nMessages[hash]) !== 'undefined') {
                message = i18nMessages[hash];
                console.log('ws.t: message: `' + hash + '` translated to : `' + message + '`');
            }

            if (typeof (params) !== 'undefined') {
                for (var search in params) {
                    message = message.replace('{' + search + '}', params[search]);
                    console.log('ws.t: param {' + search + '} replaced to : `' + params[search] + '`');
                }
            }

            return message;
        },
        i18nLoadMessages: function ($messages) {
            // clear up data pls
            i18nMessages = $messages;
        },
        roundNumber: function (number, precision) {
            precision = precision || pub.options.pricePrecision;
            const $factor = Math.pow(10, precision);
            return Math.round(number * $factor) / $factor;
        },
        numberFormat: function (number, decimal, dec_point = '.', thousands_sep = ',') {
            number = number || 0;
            decimal = decimal || pub.options.priceDecimal;
            dec_point = dec_point || '.';
            thousands_sep = thousands_sep || ',';
            decimal = decimal < 0 ? 0 : decimal;
            decimal = Math.abs(decimal);
            let i = parseInt(number = Math.abs(Number(number) || 0).toFixed(decimal)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;
            return (j ? i.substr(0, j) + thousands_sep : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep) + (decimal ? dec_point + Math.abs(number - i).toFixed(decimal).slice(2) : '');
        },
        showMoney: function (money, currency, precision) {
            precision = precision || pub.options.pricePrecision;
            currency = currency || pub.options.currency;
            money = pub.roundNumber(money, precision);
            return pub.numberFormat(money) + ' ' + currency;
        },
        showFilter: function (id) {
            if ($("#" + id).css('display') === 'none') {
                $("#" + id).css('display', 'block');
                $("#ico-" + id).removeClass('fa-chevron-down');
                $("#ico-" + id).addClass('fa-chevron-up');
            } else {
                $("#" + id).css('display', 'none');
                $("#ico-" + id).removeClass('fa-chevron-up');
                $("#ico-" + id).addClass('fa-chevron-down');
            }
        },
        // hạn chế việc khai báo event quá nhiều,
        // nếu event đã khai báo trước đó thì sẽ bị off đi
        // xử lý theo lần khai báo cuối cùng
        // nếu cả 2 cần phải được khai báo thì hay thay đổi `type`
        initEventHandler: function ($target, type, event, selector, callback) {
            var id = $target;
            if (typeof id !== 'string') {
                id = id.attr('id');
            }

            var prevHandler = eventHandlers[id];
            if (prevHandler !== undefined && prevHandler[type] !== undefined) {
                var data = prevHandler[type];

                $(document).off(data.event, data.selector);
            }
            if (prevHandler === undefined) {
                eventHandlers[id] = {};
            }
            console.log('event: "' + event + '" will be trigger with selector: "' + selector + '"');
            $(document).on(event, selector, callback);
            eventHandlers[id][type] = {event: event, selector: selector};
        },
        showLoginWallet: function () {
            $('#loginWallet').modal('show');
        },
        loginWallet: function () {
            var password = $('input[name=passwordWallet]').val();
            $('#ErrorPasswordWallet').html('');
            if (!password) {
                $('#ErrorPasswordWallet').html('Vui lòng nhập mật khẩu');
                return;
            }
            ws.loading(true);
            $.ajax({
                url: '/my-weshop/api/wallet-service/login-wallet.html',
                method: 'POST',
                data: {
                    password: password
                },
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                    } else {
                        ws.loading(false);
                        $('#ErrorPasswordWallet').html(res.message);
                    }
                }
            });
        }
    };

    return pub;
})(jQuery);

ws.initEventHandler('searchNew', 'searchBoxButton', 'click', 'button#searchBoxButton', function (event) {
    ws.browse.searchNew('input#searchBoxInput', '$url');
});
ws.initEventHandler('searchNew', 'searchBoxInput', 'keyup', 'input#searchBoxInput', function (event) {
    if (event.keyCode === 13) {
        ws.browse.searchNew(this, '$url');
    }
});