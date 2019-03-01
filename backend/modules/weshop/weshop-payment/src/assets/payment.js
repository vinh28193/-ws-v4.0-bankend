

(function ($) {
    'use strict';

    $.fn.wsPayment = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.wsPayment');
            return false;
        }
    };

    var methods = {
        init:function (options) {
            
        }
    }
})(jQuery);