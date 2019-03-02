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
        provider:undefined,
        method:undefined,

    };

    var defaultProvider = {

    };

    var defaultMethod = {

    };
    /**
     *
     */
    var paymentData = {};

    var loadedContent = null;

    var loadedProviders = [];
    var loadedMethods = [];

    var loadedBanks = [];

    var pub = {
        init: function (options) {
            var data = $.extend({}, defaults, options || {});
            console.log(data);
            console.log(paymentData);
            console.log(loadedProviders);
            console.log(loadedMethods);
            console.log(loadedBanks);
        },
        loadAjaxContent: function () {
            console.log(paymentData.method);
        },
        changeMethod: function ($name) {
            paymentData.method = $name;
            pub.loadAjaxContent();
        }
    };
    // private method
    var ajax = function (url, settings) {
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
    };
    return pub;
}(document, window, jQuery);


(function ($, ws) {
    'use strict';

    var on = function (element, event, handler) {
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
    };
    on('li[ref=cMethod]', 'click', function (e) {
        e.preventDefault();

        var that = this;
        var methodName = that.data('method-name');
        wsPayment.changeMethod(methodName);
    })
})(jQuery, wsPayment);