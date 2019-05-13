ws.browse = (function ($) {
    var pub = {
        searchNew: function ($element, baseUrl) {
            $('#loading').css('display','block');
            var temp = location.href.split('/');
            var keyword = encodeURI($($element).val());
            if(temp.length >= 4 && temp[3] !== 'search'){
                return window.location.assign('/'+temp[3]+'/search/'+keyword+'.html');
            }else {
                return window.location.assign('/search/'+keyword+'.html');
            }
            // var queryParams = {};
            // $.each(yii.getQueryParams(baseUrl), function (name, value) {
            //     queryParams[name] = value;
            // });
            // queryParams.keyword = keyword;
            // var pos = baseUrl.indexOf('?');
            // var url = pos < 0 ? baseUrl : baseUrl.substring(0, pos);
            // var hashPos = baseUrl.indexOf('#');
            // if (pos >= 0 && hashPos >= 0) {
            //     url += baseUrl.substring(hashPos);
            // }
            // url += '?' + $.param(queryParams);
        }
    };
    return pub;
})(jQuery);