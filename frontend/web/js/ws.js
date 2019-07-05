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
            pub.reloadCartBadge();
            $.ajaxPrefilter('html', function (options, originalOptions, jqXHR) {
                var orgBeforeSendHandler = options.beforeSend;
                options.beforeSend = function (xhr, settings) {
                    xhr.setRequestHeader('X-CSRF-Token', yii.getCsrfToken());
                    xhr.setRequestHeader('X-Fingerprint-Token', pub.getFingerprint());
                    xhr.setRequestHeader('X-Fingerprint-Url', options.url);
                    if (orgBeforeSendHandler && $.isFunction(orgBeforeSendHandler)) {
                        orgBeforeSendHandler(xhr, settings);
                    }
                };

                var orgErrorHandler = options.error;
                options.error = function (xhr, textStatus, errorThrown) {
                    if (!xhr) {
                        return false;
                    }
                    var redirect = (xhr.status >= 301 && xhr.status <= 303);
                    if (redirect && xhr.getResponseHeader('X-PJAX-REDIRECT-URL') !== '' && xhr.getResponseHeader('X-PJAX-REDIRECT-URL') !== null) {
                        options.url = xhr.getResponseHeader('X-PJAX-REDIRECT-URL');
                        options.replace = true;
                        console.log('Handled redirect to: ' + options.url);
                        $.pjax(options);
                    } else {
                        orgErrorHandler(xhr, textStatus, errorThrown);
                    }
                };
            });
        },
        // Todo loading
        loading: function (show) {
            show = show || false;
            $('#loading').css('display', show ? 'block' : 'none');
        },
        notifyMessage: function (message, title = 'Notify', type = 'info', size = 'default', submitClick = 'alert(\'Click!\')', cancelClick = '', confirmLabel = 'Confirm', cancelLabel = 'Close', confirmClass = 'btn btn-info', cancelClass = 'btn btn-warning',confirmAutoHide = true) {
            $('#modal-content').removeClass('modal-default');
            $('#modal-content').removeClass('modal-lg');
            $('#modal-content').removeClass('modal-xl');
            $('#modal-content').addClass('modal-' + size);
            $('#NotifyConfirmMessage').html(message);
            $('#NotifyConfirmTitle').html(title);
            $('#NotifyConfirmTitle').html(title);
            $('#NotifyConfirmBtnSubmit').css('display', 'none');
            $('#NotifyConfirmBtnSubmit').html(confirmLabel);
            $('#NotifyConfirmBtnClose').html(cancelLabel);
            $('#NotifyConfirmBtnSubmit').removeAttr('onclick');
            $('#NotifyConfirmBtnClose').removeAttr('onclick');
            $('#NotifyConfirmBtnSubmit').removeAttr('class');
            $('#NotifyConfirmBtnClose').removeAttr('class');
            $('#NotifyConfirmBtnSubmit').addClass(confirmClass);
            $('#NotifyConfirmBtnClose').addClass(cancelClass);
            $('#NotifyConfirmHeader').css('background', '#fff');
            if (type === 'confirm') {
                $('#NotifyConfirmBtnSubmit').css('display', 'block');
                if(confirmAutoHide){
                    $('#NotifyConfirmBtnSubmit').attr('onclick', "$('#NotifyConfirm').modal('hide');" + submitClick);
                }else {
                    $('#NotifyConfirmBtnSubmit').attr('onclick', submitClick);
                }
                if (cancelClick) {
                    $('#NotifyConfirmBtnClose').attr('onclick', cancelClick);
                }
            } else if (type === 'success') {
                $('#NotifyConfirmTitle').css('color', '#fff');
                $('#NotifyConfirmHeader').css('background', '#28a745');
            } else if (type === 'error') {
                $('#NotifyConfirmTitle').css('color', '#fff');
                $('#NotifyConfirmHeader').css('background', '#dc3545');
            }
            $('#NotifyConfirm').modal('show');
        },
        notifySuccess: function (message = 'Success', title = 'Success', size = 'default') {
            ws.notifyMessage(message, title, 'success', size);
        },
        notifyError: function (message = 'Error', title = 'Error', size = 'default') {
            ws.notifyMessage(message, title, 'error', size);
        },
        notifyInfo: function (message = 'Info', title = 'Info', size = 'default') {
            ws.notifyMessage(message, title, 'info', size);
        },

        notifyConfirm: function (message = 'Confirm', title = 'Confirm', size = 'default', submitClick = 'alert(\'Click!\')', cancelClick = '', confirmLabel = 'Confirm', cancelLabel = 'Close', confirmClass = 'btn btn-info', cancelClass = 'btn btn-warning',confirmAutoHide = true) {
            ws.notifyMessage(message, title, 'confirm', size, submitClick, cancelClick, confirmLabel, cancelLabel, confirmClass, cancelClass,confirmAutoHide);
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
                jqXHR.setRequestHeader('X-CSRF-Token', yii.getCsrfToken());
                jqXHR.setRequestHeader('X-Fingerprint-Token', pub.getFingerprint());
                jqXHR.setRequestHeader('X-Fingerprint-Url', url);
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
        getFingerprint: function () {
            var content = $('meta[name=fingerprint-token]').attr('content');
            if(content === ''){
                var $client = new ClientJS();
                content = $client.getFingerprint();
                pub.setFingerprint(content);
            }
            return content;
        },
        setFingerprint: function (value) {
            $('meta[name=fingerprint-token]').attr('content', value);

        },
        sendFingerprint: function () {
            setTimeout(function () {
                ws.ajax('/frontend/u', {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        fingerprint: pub.getFingerprint(),
                        path: window.location.pathname
                    },
                });
            }, 1000);
        },
        reloadCartBadge: function () {
            ws.ajax('/checkout/cart/count', function (res) {
                if(res.success){
                    pub.setCartBadge(res.count);
                }
            }, false);
        },
        setCartBadge(count) {
            $('.count-cart').html(count);
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
                // console.log('ws.t: message: `' + hash + '` translated to : `' + message + '`');
            }

            if (typeof (params) !== 'undefined') {
                for (var search in params) {
                    message = message.replace('{' + search + '}', params[search]);
                    // console.log('ws.t: param {' + search + '} replaced to : `' + params[search] + '`');
                }
            }

            return message;
        },
        i18nLoadMessages: function ($messages) {
            // clear up data pls
            i18nMessages = $messages;
        },
        roundNumber: function (number, precision) {
            number = Number(number);
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
        showMoreFilter: function (element) {
            console.log($(element).html());
            var tagert = $(element).attr('data-target');
            console.log(tagert);
            if(!$('.'+tagert).hasClass('hide-filter')){
                $('.'+tagert).addClass('hide-filter');
                $('.type-show-'+tagert).removeClass('hide-filter');
                $(element).parent().addClass('hide-filter');
            }else {
                $('.'+tagert).removeClass('hide-filter');
                $('.type-show-'+tagert).removeClass('hide-filter');
                $(element).parent().addClass('hide-filter');
            }
        },
        getSuggestSearch: function (response) {
            console.log(response);
            if(response.length > 2){
                if($('input.searchBoxInput').val() === response[0]){
                    var txt = '<option>'+response[0]+'</option>';
                    // console.log(response[1]);
                    $.each(response[1],function (k,v) {
                        txt += '<option>'+v+'</option>';
                        // console.log('<option>'+v+'</option>');
                        // $('#searchAutoComplete').append('<option>'+v+'</option>');
                    });
                    $('#listSuggestSearch').html(txt);
                }
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
            $(document).on(event, selector, callback);
            eventHandlers[id][type] = {event: event, selector: selector};
        },
        showLoginWallet: function () {
            $('#loginWallet').modal('show');
        },
        setDefaultSearch: function (portal = 'amazon') {
            ws.loading(true);
            $.ajax({
                url: '/search/set-default',
                method: 'POST',
                data: {
                    portal: portal
                },
                success: function (res) {
                    ws.loading(false);
                    if (res.success) {
                        ws.notifySuccess(res.message, 'Thành công')
                    } else {
                        ws.notifyError(res.message, 'Thất Bại')
                    }
                }
            });
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
        },
        saveDefaultAddress: function () {
            ws.loading(true);
            $.ajax({
                url: '/customer/save-address-default',
                method: 'POST',
                data: {
                    name: $('input[name=fullName_default]').val(),
                    phone: $('input[name=phone_default]').val(),
                    city_id: $('select[name=city_default]').val(),
                    district_id: $('select[name=district_default]').val(),
                    zipcode: $('#zipcode_default').val(),
                },
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                    } else {
                        ws.loading(false);
                        alert(res.message);
                    }
                }
            });
        },
        save_address: function () {
            var dataForm = {
                idAddress: $('#shipping-id').val(),
                fullName: $('#shipping-full_name').val(),
                phone: $('#shipping-phone').val(),
                email: $('#shipping-email').val(),
                district: $('#shipping_district_id').val(),
                province: $('#shipping_province_id').val(),
                zip_code: $('#shipping_zipcode').val(),
                is_default: $('#shipping_is_default:checked').val(),
                address: $('#shipping_address').val()
            };
            ws.loading(true);
            $.ajax({
                url: '/account/customer/save-address-shipping',
                method: 'POST',
                data: dataForm,
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                    } else {
                        ws.loading(false);
                        var text = res.message;
                        if(res.errors){
                            $.each(res.errors,function (k,v) {
                                text += "<br><i class='la la-dot-circle-o'></i>" + v;
                            });
                        }
                        $('#error-message').html(text);
                    }
                }
            });
        },
        province_change: function(id_province, id_district){
            var txt = '';
            district_data.filter(function (d) {
                if(d.province_id == $('#'+id_province).val()){
                    txt += '<option value="'+d.id+'">'+d.name+'</option>';
                }
            });
            $('#'+id_district).html(txt);
            $('#'+id_district).trigger('change');
            // ws.payment.calculatorShipping();
        },
        district_change: function(id_district, id_post_code , caculatorShipping = false){
            if(store_id === 7){
                var district = $('#'+id_district).val();
                var zipcode = zipcode_data.filter(z => z.district_id === district);
                if(zipcode && zipcode.length > 0){
                    $('#'+id_post_code).val(zipcode[0].zip_code);
                }else {
                    ws.notifyInfo('Cannot find zipcode from your district ');
                    $('#' + id_post_code).val('');
                    return false;
                }
            }
            if(caculatorShipping){
                ws.payment.calculatorShipping();
            }
        },
        zipcode_keyup: function(id_zipcode, id_list_zipcode){
            var txt = '';
            var count = 0;
            var zipcode = $('#'+id_zipcode).val();
            zipcode_data.filter(function (z) {
                if(count < 20 && (z.zip_code.indexOf(zipcode) > -1 || !zipcode )){
                    count++;
                    txt += "<option value='"+z.zip_code+"'>"+z.label+"</option>";
                }
            });
            $('#'+id_list_zipcode).html(txt);
        },
        zipcode_Change: function(id_zipcode, province_id, district_id){
            var zipcode = zipcode_data.filter(z => z.zip_code === $('#' + id_zipcode).val());
            if(zipcode && zipcode.length > 0){
                $('#'+province_id).val(zipcode[0].province_id).trigger('change');
                $('#'+district_id).val(zipcode[0].district_id).trigger('change');
            }else {
                ws.notifyInfo('Zip code '+$('#' + id_zipcode).val()+' not support! Please change zip code');
                $('#' + id_zipcode).val('');
            }
        },
        showModal: function (id) {
          $('#'+id).modal();
        },
        editAddress: function(id) {
            ws.loading(true);
            $.ajax({
                url: '/account/customer/edit-address',
                method: 'POST',
                data: {id: id},
                success: function (res) {
                    if (res.success) {
                        ws.loading(false);
                        ws.notifyConfirm(res.data.content,res.data.title,'default','ws.save_address()','',ws.t('Confirm'),ws.t('Close'),'btn btn-success','btn btn-warning',false);
                    } else {
                        ws.loading(false);
                        ws.notifyError(res.message);
                    }
                }
            });
        },
        removeAddress: function(id) {
            ws.loading(true);
            $.ajax({
                url: '/account/customer/remove-address',
                method: 'POST',
                data: {id: id},
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                    } else {
                        ws.loading(false);
                        ws.notifyError(res.message);
                    }
                }
            });
        },
        startForm: function () {
            var link = location.href;
            var phone = $('#shippingform-buyer_phone').val().trim();
            phone = phone.replace('(+84)', '0');
            phone = phone.replace('+84', '0');
            phone = phone.replace('0084', '0');
            phone = phone.replace('+62', '0');
            phone = phone.replace('0062', '0');
            phone = phone.replace(/ /g, '');
            var firstNumber = phone.substring(0, 2);
            if ((firstNumber == '09' || firstNumber == '03' || firstNumber == '07' || firstNumber == '08' || firstNumber == '05' || firstNumber == '06') && phone.length < 13 && phone.length > 9) {
                ws.ajax('/checkout/shipping/add-cart-checkout', {
                    type: 'POST',
                    data: {
                        phone: phone,
                        link: link,
                        fullName: $('#shippingform-buyer_name').val().trim(),
                        email: $('#shippingform-buyer_email').val().trim(),
                        typeUpdate: 'updateCartInCheckout'
                    },
                });
            }
        },
    };

    return pub;
})(jQuery);

ws.initEventHandler('saveDefaultAddress', 'searchBoxButton', 'click', 'button#searchBoxButton', function (event) {
    ws.browse.searchNew('input.searchBoxInput', '$url');
});
ws.initEventHandler('setDefaultAddressSmBtn', 'setDefaultAddressSmBtn', 'click', 'button#setDefaultAddressSmBtn', function (event) {
    ws.saveDefaultAddress();
});
ws.initEventHandler('searchNew', 'searchBoxInput', 'keyup', 'input.searchBoxInput', function (event) {
    clearTimeout(window.mytimeout);
    if (event.keyCode === 13) {
        ws.browse.searchNew(this, '$url');
    }else{
        var $element = this;
        var key = $(this).val();
        if(key){
            window.mytimeout = setTimeout(function(){
                var url_call = 'https://completion.amazon.com/search/complete?method=completion&mkt=1&r=QHW0T16FVMD8GWM2WWM4&s=161-1591289-5903765&c=AWJECJG5N87M8&p=Detail&l=en_US&sv=desktop&client=amazon-search-ui&search-alias=aps&qs=&cf=1&fb=1&sc=1&q='+encodeURI(key);
                $.ajax({
                    url: url_call,
                    dataType: 'jsonp',
                    jsonpCallback: "ws.getSuggestSearch",
                });
            }, 200);
        }
    }
});
ws.initEventHandler('searchNew', 'mb-searchBoxInput', 'keyup', 'input.mb-searchBoxInput', function (event) {
    clearTimeout(window.mytimeout);
    if (event.keyCode === 13) {
        ws.browse.searchNew(this, '$url');
    }else if (event.keyCode){
        var $element = this;
        var key = $(this).val();
        if(key){
            window.mytimeout = setTimeout(function(){
                var url_call = 'https://completion.amazon.com/search/complete?method=completion&mkt=1&r=QHW0T16FVMD8GWM2WWM4&s=161-1591289-5903765&c=AWJECJG5N87M8&p=Detail&l=en_US&sv=desktop&client=amazon-search-ui&search-alias=aps&qs=&cf=1&fb=1&sc=1&q='+encodeURI(key);
                $.ajax({
                    url: url_call,
                    dataType: 'jsonp',
                    jsonpCallback: "ws.getSuggestSearch",
                });
            }, 200);
        }
    }
});

$('input.searchBoxInput').change(function () {
    // clearTimeout(window.mytimeout);
    ws.browse.searchNew(this, '$url');
});
$('datalist#listSuggestSearch').keyup(function () {
    console.log('Key up: '+event.key);
    if (event.keyCode === 13) {
        ws.browse.searchNew(this, '$url');
    }
});
$('#zipcode_default').keyup(function () {
    var txt = '';
    var count = 0;
    var tesst = zipcode_data.filter(function (z) {
        if(z.zip_code.indexOf($('#zipcode_default').val()) > -1){
            count++;
        }
        return count < 20 && z.zip_code.indexOf($('#zipcode_default').val()) > -1;
    });
    $.each(tesst, function (k,v) {
        txt += "<option value='"+v.zip_code+"'>"+v.label+"</option>";
    });
    $('#list_zipcode').html(txt);
});