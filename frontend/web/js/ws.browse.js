ws.browse = (function ($) {
    var pub = {
        searchNew: function ($element, baseUrl) {
            var keyword = encodeURI($($element).val());
            console.log(keyword);
            var a = keyword;
            if (/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(a)) {
                var s = "undefined", o = "undefined",
                    i = /(ftp|http|https):\/\/www.ebay.com\/itm\/([\S]*\/[0-9]*(\S+)|[0-9]([\/]*\S+))?/;
                if (/(ftp|http|https):\/\/www.amazon.com\/(gp\/product|[0-9a-zA-Z\-]*\/dp|dp)(\S+)?/.test(a)) s = /(ftp|http|https):\/\/www.amazon.com\/dp(\S+)?/.test(a) ? (s = a.split("/")[4]).split("?")[0] : (s = a.split("/")[5]).split("?")[0], o = "amazon"; else if (i.test(a)) s = /(ftp|http|https):\/\/www.ebay.com\/itm\/[\S]*\/[0-9]*(\S+)?/.test(a) ? (s = a.split("/")[5]).split("?")[0] : (s = a.split("/")[4]).split("?")[0], o = "ebay"; else {
                    $($element).val('');
                    return ws.notifyError('Your link is not valid.\n' +
                        'Only support the link of product detail (Ebay or Amazon), try another or search with keyword !');
                }
                return window.location.assign('/'+o+'/item/item-search-'+s+'.html');
            }else {
                ws.loading(true);
                var temp = location.href.split('/');
                if(!keyword){
                    ws.loading(false);
                    return false;
                }
                if(temp.length >= 4 && temp[3] && temp[3] !== 'search' && temp[3] !== '' && (temp[3] === 'amazon' || temp[3] === 'ebay')){
                    return window.location.assign('/'+temp[3].replace('.html','')+'/search/'+keyword+'.html');
                }else {
                    return window.location.assign('/search/'+keyword+'.html');
                }
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