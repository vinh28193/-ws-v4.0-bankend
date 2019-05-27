function number_format(r, t, n, i) {
    r = (r + "").replace(/[^0-9+\-Ee.]/g, "");
    var a = isFinite(+r) ? +r : 0, e = isFinite(+t) ? Math.abs(t) : 0, u = void 0 === i ? "," : i,
        o = void 0 === n ? "." : n, l = "";
    return (l = (e ? function (r, t) {
        var n = Math.pow(10, t);
        return "" + Math.round(r * n) / n
    }(a, e) : "" + Math.round(a)).split("."))[0].length > 3 && (l[0] = l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, u)), (l[1] || "").length < e && (l[1] = l[1] || "", l[1] += new Array(e - l[1].length + 1).join("0")), l.join(o)
}

var UrlUtility = {};
UrlUtility.getUrlVars = function () {
    var r = window.location.href;
    return $.parseParams(r.split("?")[1] || "")
}, UrlUtility.buildUrl = function (r) {
    var t = "", n = 0;
    return $.each(r, function (r, i) {
        var a = 0 == n ? "?" : "&";
        $.isArray(i) ? $.each(i, function (i, e) {
            t += (a = 0 == n ? "?" : "&") + r + "[]=" + e, n++
        }) : t += a + r + "=" + i, n++
    }), t
};
var ultis = {};
ultis.numberFormat = function (r, t, n) {
    if (t || (t = ","), "," == t) {
        if (n) {
            var i = r / 1e3;
            return number_format(1e3 * Math.round(i), 0, ",", ".")
        }
        return number_format(r, 2, ".", ",")
    }
    return number_format(r, 2, ".", ",")
};