var textutils = {};
textutils.encode_keyword = function (e) {
    return null === e || "" === e ? "" : e.replace(/ /g, "+").toLowerCase()
}, Number.prototype.toMoney = function (e, t, r) {
    var n = this, i = isNaN(e) ? 2 : Math.abs(e), o = t || ".", a = void 0 === r ? "," : r, c = n < 0 ? "-" : "",
        u = parseInt(n = Math.abs(n).toFixed(i)) + "", l = (l = u.length) > 3 ? l % 3 : 0;
    return c + (l ? u.substr(0, l) + a : "") + u.substr(l).replace(/(\d{3})(?=\d)/g, "$1" + a) + (i ? o + Math.abs(n - u).toFixed(i).slice(2) : "")
}, textutils.drawAlias = function (e) {
    $("input[data-alias=alias]").val(textutils.createAlias($(e).val()))
}, textutils.createAlias = function (e) {
    return null === e || "" === e ? "" : textutils.removeDiacritical(e).replace(/\W/g, "-").toLowerCase()
}, textutils.removeDiacritical = function (e) {
    return e = e.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, "a"), e = e.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, "e"), e = e.replace(/(ì|í|ị|ỉ|ĩ)/g, "i"), e = e.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, "o"), e = e.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, "u"), e = e.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, "y"), e = e.replace(/(đ)/g, "d"), e = e.replace(/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/g, "A"), e = e.replace(/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/g, "E"), e = e.replace(/(Ì|Í|Ị|Ỉ|Ĩ)/g, "I"), e = e.replace(/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/g, "O"), e = e.replace(/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/g, "U"), e = e.replace(/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/g, "Y"), e = e.replace(/(Đ)/g, "D")
}, textutils.hashParam = function () {
    var e, t, r = /\+/g, n = /([^&=]+)=?([^&]*)/g, i = function (e) {
        return decodeURIComponent(e.replace(r, " "))
    }, o = $(location).attr("hash").replace("#", "").split("?")[1];
    if (void 0 === o) return {};
    for (e = {}; t = n.exec(o);) e[i(t[1])] = i(t[2]);
    return e
}, textutils.queryParam = function () {
    var e, t, r = /\+/g, n = /([^&=]+)=?([^&]*)/g, i = function (e) {
        return decodeURIComponent(e.replace(r, " "))
    }, o = window.location.search.substring(1);
    for (e = {}; t = n.exec(o);) e[i(t[1])] = i(t[2]);
    return e
}, textutils.buildQuery = function (e) {
    var t = "";
    return $.each(e, function (e, r) {
        void 0 !== r && null != r && "" != r && (1 === i ? t += "?" : t += "&", t += e + "=" + r, i++)
    }), "" == t ? "?" : t
}, textutils.formatTime = function (e, t) {
    var r = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
    e = parseFloat(1e3 * e);
    var n = new Date(e), i = n.getFullYear(), o = r[n.getMonth()], a = n.getDate(), c = n.getHours(),
        u = n.getMinutes(), l = n.getSeconds(), e = "";
    return e = "day" === t ? a + "/" + o + "/" + i : "hour" === t ? c + ":" + u + " " + a + "/" + o + "/" + i : "time" === t ? a + "/" + o + "/" + i + " " + c + ":" + u + ":" + l : c + ":" + u + ":" + l + " " + a + "/" + o + "/" + i
}, textutils.percentFormat = function (e, t) {
    var r = 0;
    return e > t && (r = (e - t) / e), r *= 100, (r = Math.ceil(r)).toMoney(0, ",", ".")
}, textutils.ucfirst = function (e) {
    return (e += "").charAt(0).toUpperCase() + e.substr(1)
}, textutils.timeConverter = function (e) {
    var t = new Date(e), r = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"], n = t.getFullYear(),
        i = r[t.getMonth()], o = t.getDate(), a = t.getHours(), c = t.getMinutes();
    t.getSeconds();
    return e = +a + ":" + c + " - " + o + "/" + i + "/" + n
}, textutils.startPrice = function (e, t, r) {
    return !r && e <= t ? "0" : (r && e <= t && (e = t), e > 0 ? e.toMoney(0, ",", ".") : "")
}, textutils.sellPrice = function (sellPrice, discount, discountPrice, discountPercent) {
    return discount && (sellPrice = discountPercent > 0 ? eval(sellPrice) * (100 - eval(discountPercent)) / 100 : eval(sellPrice) - eval(discountPrice)), sellPrice.toMoney(0, ",", ".")
}, textutils.discountPrice = function (startPrice, sellPrice, discount, discountPrice, discountPercent) {
    discount && startPrice <= sellPrice && (startPrice = sellPrice), discount && (sellPrice = discountPercent > 0 ? eval(sellPrice) * (100 - eval(discountPercent)) / 100 : eval(sellPrice) - eval(discountPrice));
    var price = eval(startPrice) - eval(sellPrice);
    return (price = price > 0 ? price : 0).toMoney(0, ",", ".")
}, textutils.numberFormat = function (e) {
    return e.toMoney(0, ",", ".")
}, textutils.createAlias = function (e) {
    return null === e || "" === e ? "" : textutils.removeDiacritical(e).replace(/\W/g, "-").toLowerCase()
}, textutils.buildQuery = function (e) {
    var t = 1, r = "";
    return $.each(e, function (e, n) {
        void 0 !== n && null !== n && "" !== n && (r += 1 === t ? "?" : "&", r += e + "=" + n, t++)
    }), r
}, textutils.isProperty = function (e) {
    return -1 === ["category_link", "interpark_disp_no", "interpark_disp_nm", "siteId", "site_domain", "site_config", "interpark_no", "interpark_ord_age_rstr_yn", "interpark_ord_rstr_age", "interpark_sale_unitcost", "interpark_biz_tp", "interpark_entr_nm", "interpark_entr_seller_nm", "interpark_hdelv_mafc_entr_nm", "interpark_icn_img_url", "interpark_list_img_url", "interpark_main_img_url", "interpark_main_nm", "category", "category_path", "feeShip", "usTax", "feeMore", "coefficient", "rate", "ebay_sellerId", "ebay_categoryId", "ebay_categoryName", "ebay_usShipping", "ebay_usTax", "ebay_condition", "ebay_sellPrice"].indexOf(e)
}, textutils.countCart = function (obj) {
    var count = 0;
    return $.each(obj, function () {
        $.each(this, function (key, value) {
            "order" == key && (count += eval(value.items.length))
        })
    }), count
}, textutils.getCookie = function (e) {
    return document.cookie.length > 0 && (c_start = document.cookie.indexOf(e + "="), -1 != c_start) ? (c_start = c_start + e.length + 1, c_end = document.cookie.indexOf(";", c_start), -1 == c_end && (c_end = document.cookie.length), unescape(document.cookie.substring(c_start, c_end))) : ""
}, textutils.saveCookie = function (e, t, r) {
    if (e) {
        var n = new Date;
        n.setTime(n.getTime() + 24 * e * 60 * 60 * 1e3);
        i = "; expires=" + n.toGMTString()
    } else var i = "";
    document.cookie = t + "=" + r + i
}, textutils.formatPriceVND = function (e) {
    return e.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}, textutils.encodeBase64 = function (e) {
    var t = {
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
            var r, n, i, o, a, c, u, l = "", s = 0;
            for (e = t._utf8_encode(e); s < e.length;) o = (r = e.charCodeAt(s++)) >> 2, a = (3 & r) << 4 | (n = e.charCodeAt(s++)) >> 4, c = (15 & n) << 2 | (i = e.charCodeAt(s++)) >> 6, u = 63 & i, isNaN(n) ? c = u = 64 : isNaN(i) && (u = 64), l = l + this._keyStr.charAt(o) + this._keyStr.charAt(a) + this._keyStr.charAt(c) + this._keyStr.charAt(u);
            return l
        }, decode: function (e) {
            var r, n, i, o, a, c, u = "", l = 0;
            for (e = e.replace(/[^A-Za-z0-9\+\/\=]/g, ""); l < e.length;) r = this._keyStr.indexOf(e.charAt(l++)) << 2 | (o = this._keyStr.indexOf(e.charAt(l++))) >> 4, n = (15 & o) << 4 | (a = this._keyStr.indexOf(e.charAt(l++))) >> 2, i = (3 & a) << 6 | (c = this._keyStr.indexOf(e.charAt(l++))), u += String.fromCharCode(r), 64 != a && (u += String.fromCharCode(n)), 64 != c && (u += String.fromCharCode(i));
            return u = t._utf8_decode(u)
        }, _utf8_encode: function (e) {
            e = e.replace(/\r\n/g, "\n");
            for (var t = "", r = 0; r < e.length; r++) {
                var n = e.charCodeAt(r);
                n < 128 ? t += String.fromCharCode(n) : n > 127 && n < 2048 ? (t += String.fromCharCode(n >> 6 | 192), t += String.fromCharCode(63 & n | 128)) : (t += String.fromCharCode(n >> 12 | 224), t += String.fromCharCode(n >> 6 & 63 | 128), t += String.fromCharCode(63 & n | 128))
            }
            return t
        }, _utf8_decode: function (e) {
            for (var t = "", r = 0, n = c1 = c2 = 0; r < e.length;) (n = e.charCodeAt(r)) < 128 ? (t += String.fromCharCode(n), r++) : n > 191 && n < 224 ? (c2 = e.charCodeAt(r + 1), t += String.fromCharCode((31 & n) << 6 | 63 & c2), r += 2) : (c2 = e.charCodeAt(r + 1), c3 = e.charCodeAt(r + 2), t += String.fromCharCode((15 & n) << 12 | (63 & c2) << 6 | 63 & c3), r += 3);
            return t
        }
    };
    return t.encode(e)
}, textutils.decodeBase64 = function (e) {
    var t = {
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
            var r, n, i, o, a, c, u, l = "", s = 0;
            for (e = t._utf8_encode(e); s < e.length;) o = (r = e.charCodeAt(s++)) >> 2, a = (3 & r) << 4 | (n = e.charCodeAt(s++)) >> 4, c = (15 & n) << 2 | (i = e.charCodeAt(s++)) >> 6, u = 63 & i, isNaN(n) ? c = u = 64 : isNaN(i) && (u = 64), l = l + this._keyStr.charAt(o) + this._keyStr.charAt(a) + this._keyStr.charAt(c) + this._keyStr.charAt(u);
            return l
        }, decode: function (e) {
            var r, n, i, o, a, c, u = "", l = 0;
            for (e = e.replace(/[^A-Za-z0-9\+\/\=]/g, ""); l < e.length;) r = this._keyStr.indexOf(e.charAt(l++)) << 2 | (o = this._keyStr.indexOf(e.charAt(l++))) >> 4, n = (15 & o) << 4 | (a = this._keyStr.indexOf(e.charAt(l++))) >> 2, i = (3 & a) << 6 | (c = this._keyStr.indexOf(e.charAt(l++))), u += String.fromCharCode(r), 64 != a && (u += String.fromCharCode(n)), 64 != c && (u += String.fromCharCode(i));
            return u = t._utf8_decode(u)
        }, _utf8_encode: function (e) {
            e = e.replace(/\r\n/g, "\n");
            for (var t = "", r = 0; r < e.length; r++) {
                var n = e.charCodeAt(r);
                n < 128 ? t += String.fromCharCode(n) : n > 127 && n < 2048 ? (t += String.fromCharCode(n >> 6 | 192), t += String.fromCharCode(63 & n | 128)) : (t += String.fromCharCode(n >> 12 | 224), t += String.fromCharCode(n >> 6 & 63 | 128), t += String.fromCharCode(63 & n | 128))
            }
            return t
        }, _utf8_decode: function (e) {
            for (var t = "", r = 0, n = c1 = c2 = 0; r < e.length;) (n = e.charCodeAt(r)) < 128 ? (t += String.fromCharCode(n), r++) : n > 191 && n < 224 ? (c2 = e.charCodeAt(r + 1), t += String.fromCharCode((31 & n) << 6 | 63 & c2), r += 2) : (c2 = e.charCodeAt(r + 1), c3 = e.charCodeAt(r + 2), t += String.fromCharCode((15 & n) << 12 | (63 & c2) << 6 | 63 & c3), r += 3);
            return t
        }
    };
    return t.decode(e)
}, textutils.getLanguageByKey = function (e, t) {
    return e || t
};