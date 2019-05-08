var ws = ws || (function ($) {

    var pub = {
        init: function () {
            console.log('js init completed');
        },
        loading: function (show) {
            show = show || false;
            console.log('loading ' + (show ? 'start' : 'end'));
        },
        ajax: function (url, $options, loading = false) {
            if (loading) {
                pub.loading(true);
            }
            // ajax('url',function(response, textStatus, xhr){ // handel}) => meaning send with default setting
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
        }
    };

    return pub;
})(jQuery);

$(function () {
    ws.init();
});