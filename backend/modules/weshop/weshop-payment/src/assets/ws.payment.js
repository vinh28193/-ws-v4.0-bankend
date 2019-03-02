/**
 *
 */

var wsPayment = wsPayment || function (d, w, $) {

    /**
     * @type
     */
    var defaults = {
        store: undefined,
        page: undefined,
        providers: [],
        methods:[]

    };

    /**
     *
     */
    var paymentData = {};

    var loadedContent = null;

    // public method
    var pub = {
        init: function (options) {
            paymentData = $.extend(paymentData, defaults, options || {});
        },
        on: function (element, event, handler) {
            if (element instanceof NodeList) {
                element.forEach(function (value) {
                    value.addEventListener(event, handler, false);
                });
                return;
            }
            if (!(element instanceof Array)) {
                element = [element];
            }
            for (var i in element) {
                if (typeof element[i].addEventListener !== 'function') {
                    continue;
                }
                element[i].addEventListener(event, handler, false);
            }
        },
        ajax: function (url, settings) {
            var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            settings = settings || {};
            xhr.open(settings.method || 'GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'text/html');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200 && settings.success) {
                        settings.success(xhr);
                    } else if (xhr.status !== 200 && settings.error) {
                        settings.error(xhr);
                    }
                }
            };
            xhr.send(settings.data || '');
        },
        changeMethod: function ($name) {
            var activeMethod = paymentData.methods[$name];
            if(typeof activeMethod === 'undefined'){

            }
            console.log(activeMethod);
        }
    };
    // private method

    return pub;
}(document, window, jQuery);




(function ($, ws) {
    'use strict';
    
    ws.on('li[ref=method]','click', function ($event) {
        
    })
})(jQuery, wsPayment);