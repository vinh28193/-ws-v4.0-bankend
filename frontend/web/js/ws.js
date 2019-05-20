var ws = ws || (function ($) {

    var pub = {
        i18nMessages: [],
        eventHandlers: {},
        init: function () {
            // console.log('js ws init completed');
            console.log(pub.numberFormat(12345.67788,-3));
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
            $options.success = success;
            $options.error = error;
            $options.url = url;

            $.ajax($options);
        },
        goback: function () {
            history.back()
        },
        redirect: function (href) {
            document.location.href = href;
        },
        sweetalert: function (smg, type, options) {
            alert(type + ':' + smg);
        },
        i18n: function (category, message, params = [], language = null) {

        },
        i18nLoadMessages: function ($messages) {
            // clear up data pls
            pub.i18nMessages = $messages;
        },
        roundNumber: function (number, precision) {
            precision = precision || 0;
            const $factor = Math.pow(10, precision);
            return Math.round(number * $factor) / $factor;
        },
        numberFormat: function (number, decimal = 2, dec_point = '.', thousands_sep = ',') {
            number = number || 0;
            decimal = decimal || 0;
            dec_point = dec_point || '.';
            thousands_sep = thousands_sep || ',';
            number = pub.roundNumber(number, decimal);
            decimal = decimal < 0 ? 0 : decimal;
            decimal = Math.abs(decimal);
            let i = parseInt(number = Math.abs(Number(number) || 0).toFixed(decimal)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;
            return (j ? i.substr(0, j) + thousands_sep : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep) + (decimal ? dec_point + Math.abs(number - i).toFixed(decimal).slice(2) : '');
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

            var prevHandler = pub.eventHandlers[id];
            if (prevHandler !== undefined && prevHandler[type] !== undefined) {
                var data = prevHandler[type];

                $(document).off(data.event, data.selector);
            }
            if (prevHandler === undefined) {
                pub.eventHandlers[id] = {};
            }
            // console.log('event: "' + event + '" will be trigger with selector: "' + selector + '"');
            $(document).on(event, selector, callback);
            pub.eventHandlers[id][type] = {event: event, selector: selector};
        }
    };

    return pub;
})(jQuery);

$(function () {
    ws.init();
});
ws.initEventHandler('searchNew', 'searchBoxButton', 'click', 'button#searchBoxButton', function (event) {
    ws.browse.searchNew('input#searchBoxInput', '$url');
});
ws.initEventHandler('searchNew', 'searchBoxInput', 'keyup', 'input#searchBoxInput', function (event) {
    if (event.keyCode === 13) {
        ws.browse.searchNew(this, '$url');
    }
});