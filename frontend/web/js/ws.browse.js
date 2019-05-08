ws.browse = (function ($) {
    var pub = {
        searchNew: function ($element, baseUrl) {
            var keyword = $($element).val();
            var queryParams = {};
            $.each(yii.getQueryParams(baseUrl), function (name, value) {
                queryParams[name] = value;
            });
            queryParams.keyword = keyword;
            var pos = baseUrl.indexOf('?');
            var url = pos < 0 ? baseUrl : baseUrl.substring(0, pos);
            var hashPos = baseUrl.indexOf('#');
            if (pos >= 0 && hashPos >= 0) {
                url += baseUrl.substring(hashPos);
            }
            url += '?' + $.param(queryParams);
            return window.location.href = url;
        }
    };
    return pub;
})(jQuery);