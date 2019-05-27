!function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e(jQuery)
}(function (e) {
    function n(e) {
        return u.raw ? e : encodeURIComponent(e)
    }

    function o(e) {
        return u.raw ? e : decodeURIComponent(e)
    }

    function i(e) {
        return n(u.json ? JSON.stringify(e) : String(e))
    }

    function r(e) {
        0 === e.indexOf('"') && (e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
        try {
            return e = decodeURIComponent(e.replace(c, " ")), u.json ? JSON.parse(e) : e
        } catch (e) {
        }
    }

    function t(n, o) {
        var i = u.raw ? n : r(n);
        return e.isFunction(o) ? o(i) : i
    }

    var c = /\+/g, u = e.cookie = function (r, c, a) {
        if (void 0 !== c && !e.isFunction(c)) {
            if ("number" == typeof(a = e.extend({}, u.defaults, a)).expires) {
                var d = a.expires, f = a.expires = new Date;
                f.setTime(+f + 864e5 * d)
            }
            return document.cookie = [n(r), "=", i(c), a.expires ? "; expires=" + a.expires.toUTCString() : "", a.path ? "; path=" + a.path : "", a.domain ? "; domain=" + a.domain : "", a.secure ? "; secure" : ""].join("")
        }
        for (var s = r ? void 0 : {}, p = document.cookie ? document.cookie.split("; ") : [], m = 0, v = p.length; m < v; m++) {
            var x = p[m].split("="), k = o(x.shift()), l = x.join("=");
            if (r && r === k) {
                s = t(l, c);
                break
            }
            r || void 0 === (l = t(l)) || (s[k] = l)
        }
        return s
    };
    u.defaults = {}, e.removeCookie = function (n, o) {
        return void 0 !== e.cookie(n) && (e.cookie(n, "", e.extend({}, o, {expires: -1})), !e.cookie(n))
    }
});