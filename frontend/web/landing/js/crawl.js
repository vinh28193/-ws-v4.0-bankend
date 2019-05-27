function ValidURL(e) {
    return /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(e)
}

var crawl = {};
crawl.event = null, crawl.redirect = null, crawl.value = null, crawl.calPrice = function () {
    priceDollar = $("input[name=productPrice]").val(), exRate = $("input[name=exRate]").val(), usShip = $("input[name=productShipping]").val(), currency = $("input[name=currencySymbol]").val(), storeId = $("input[name=storeId]").val(), quantity = $("select[name=quantity]").val(), customerGroupId = $("input[name=customerGroupId]").val(), crawl.changePrice(priceDollar, exRate, usShip, currency, quantity, customerGroupId, storeId)
}, crawl.init = function () {
}, crawl.changePrice = function (e, a, t, r, s, o, l) {
    $.isNumeric(e) || (e = 0), $.isNumeric(a) || (a = 22e3), $.isNumeric(t) || (t = 0), $.isNumeric(s) || (s = 1), $.isNumeric(o) || (o = 1);
    var c = e * s * a, n = (8 + .09 * e) * s * a;
    if (1 == l) {
        if (s <= 1) u = 11; else u = 10 * s;
        var i = 7.5 * s, d = t * s;
        if (2 == o) h = .05 * (1.09 * e + 8) * s * a; else h = .08 * (1.09 * e + 8) * s * a
    } else if (6 == l) var u = 20 * s, i = e * s * .06, d = t * s, h = .1 * (c + n); else if (7 == l) var u = 24 * s,
        i = 0, d = 0, h = .1 * (c + n); else if (9 == l) var u = 20 * s, i = 0, d = t * s,
        h = (c + n) / 2 * .1; else if (10 == l) var u = 20 * s, i = e * s * .1, d = t * s, h = .1 * (c + n);
    var p = (u + i + d) * a, w = n + p + h, m = c + w, y = !1;
    if ("đ" !== r && "VNĐ" !== r || (y = !0), 1 == l) $("[rel=priceUs]").text(ultis.numberFormat(c, ",", y) + " " + r), $("[rel=totalShipping]").text(ultis.numberFormat(w, ",", y) + " " + r), $("[rel=usShippingTax]").text(ultis.numberFormat(n + h / 2, ",", y) + " " + r), $("[rel=dutiesTax]").text(ultis.numberFormat(p + h / 2, ",", y) + " " + r), $("[rel=totalPriceLocal]").text(ultis.numberFormat(m, ",", y) + " " + r); else if ($("[rel=priceUs]").text(ultis.numberFormat(c, ",", y) + " " + r), $("[rel=totalShipping]").text(ultis.numberFormat(w, ",", y) + " " + r), $("[rel=usShippingTax]").text(ultis.numberFormat(n, ",", y) + " " + r), $("[rel=dutiesTax]").text(ultis.numberFormat(p, ",", y) + " " + r), $("[rel=totalPriceLocal]").text(ultis.numberFormat(m, ",", y) + " " + r), 9 == l) {
        var f = (new Date).getTime();
        f >= 1501520400 && f <= 15028164e5 ? ($("[rel=weshopFee]").text(ultis.numberFormat(2 * h, ",", y) + " " + r), $("[rel=saleoffFee]").text("- " + ultis.numberFormat(h, ",", y) + " " + r)) : $("[rel=weshopFee]").text(ultis.numberFormat(h, ",", y) + " " + r)
    } else $("[rel=weshopFee]").text(ultis.numberFormat(h, ",", y) + " " + r)
}, crawl.choseCat = function (e, a, t, r) {
    $("li[rel=catOption]").removeClass("active"), "global" == e ? ($("#header-search-icon").html('<i class="drop-ico"></i><i class="ws-ico"></i>'), $("input[name=portal]").val(e)) : "ebay" == e ? ($("#header-search-icon").html('<i class="drop-ico"></i><i class="ebay-ico"></i>'), $("input[name=portal]").val(e)) : "amazon" == e ? ($("#header-search-icon").html('<i class="drop-ico"></i><i class="amz-ico"></i>'), $("input[name=portal]").val(e)) : "category" == e && ($("#chooseCate-" + a).addClass("active"), $("#header-search-icon").html('<i class="drop-ico"></i><span>' + t + "</span>"), $("input[name=portal]").val(r)), $("#header-search-title").attr("title", t), $("input[name=chooseCategory]").val(a)
}, crawl.searchCat = function () {
    var e = $("#searchKeyword").val();
    if ((e = e.replace(" ", "+")).length > 2) {
        var a = $("#searchCategory").val(), t = "";
        0 != a && (t = UrlUtility.buildUrl({categoryIds: [a]})), window.location = baseUrl + "ebay/search/" + e + ".html" + t
    } else popup.msg("Please enter a keyword to search !"), $("#searchKeyword").focus()
}, crawl.searchAlias = function (e) {
    var a = $("input[name=" + e + "]").val().trim();
    console.log(a), crawl.search(a)
}, crawl.search = function (e) {
    var a = $("input[name=portal]").val(), t = $("input[name=chooseCategory]").val();
    if (e || (e = $("input[name=search]").val().trim()), ValidURL(e)) {
        var r = {url: e}, s = jQuery.param(r);
        if (e.length > 4) return void ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: e},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (e) {
                e.success ? window.location.href = e.data.url : (console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s)
            }
        })
    }
    ajax({
        service: "weshop/service/crawl/input.json",
        data: {keyword: e},
        contentType: "json",
        type: "post",
        loading: !1,
        done: function (r) {
            if (r.success) {
                if (0 === e.indexOf("http://") || 0 === e.indexOf("https://")) r.data.cart && (clearTimeout(crawl.redirect), crawl.redirect = setTimeout(function () {
                    console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s
                }, 100)); else if (!r.data.cart) return r.data.keyword = encodeURIComponent(r.data.keyword), "amazon" == a ? (window.location = t ? baseUrl + "amazon/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "amazon/search/" + r.data.keyword + ".html", !1) : "ebay" == a ? (window.location = t ? baseUrl + "ebay/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "ebay/search/" + r.data.keyword + ".html", !1) : (window.location = baseUrl + "search/" + r.data.keyword + ".html", !1)
            } else popup.msg(r.message), crawl.initShow();
            crawl.keywordSuccess()
        }
    })
}, crawl.searchLanding = function (e) {
    var a = $("input[name=portal]").val(), t = $("input[name=chooseCategory]").val();
    if (e || (e = $("input[name=searchLanding]").val().trim()), console.log(e), ValidURL(e)) {
        var r = {url: e}, s = jQuery.param(r);
        if (e.length > 4) return void ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: e},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (e) {
                e.success ? window.location.href = e.data.url : (console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s)
            }
        })
    }
    ajax({
        service: "weshop/service/crawl/input.json",
        data: {keyword: e},
        contentType: "json",
        type: "post",
        loading: !1,
        done: function (r) {
            if (r.success) {
                if (0 === e.indexOf("http://") || 0 === e.indexOf("https://")) r.data.cart && (clearTimeout(crawl.redirect), crawl.redirect = setTimeout(function () {
                    console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s
                }, 100)); else if (!r.data.cart) return r.data.keyword = encodeURIComponent(r.data.keyword), "amazon" == a ? (window.location = t ? baseUrl + "amazon/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "amazon/search/" + r.data.keyword + ".html", !1) : "ebay" == a ? (window.location = t ? baseUrl + "ebay/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "ebay/search/" + r.data.keyword + ".html", !1) : (window.location = baseUrl + "search/" + r.data.keyword + ".html", !1)
            } else popup.msg(r.message), crawl.initShow();
            crawl.keywordSuccess()
        }
    })
}, crawl.searchAbout = function (e) {
    var a = $("input[name=portal]").val(), t = $("input[name=chooseCategory]").val();
    if (e || (e = $("input[name=searchAbout]").val().trim()), console.log(e), ValidURL(e)) {
        var r = {url: e}, s = jQuery.param(r);
        if (e.length > 4) return void ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: e},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (e) {
                e.success ? window.location.href = e.data.url : (console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s)
            }
        })
    }
    ajax({
        service: "weshop/service/crawl/input.json",
        data: {keyword: e},
        contentType: "json",
        type: "post",
        loading: !1,
        done: function (r) {
            if (r.success) {
                if (0 === e.indexOf("http://") || 0 === e.indexOf("https://")) r.data.cart && (clearTimeout(crawl.redirect), crawl.redirect = setTimeout(function () {
                    console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + s
                }, 100)); else if (!r.data.cart) return r.data.keyword = encodeURIComponent(r.data.keyword), "amazon" == a ? (window.location = t ? baseUrl + "amazon/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "amazon/search/" + r.data.keyword + ".html", !1) : "ebay" == a ? (window.location = t ? baseUrl + "ebay/search/" + r.data.keyword + ".html?page=1&cat[]=" + t : baseUrl + "ebay/search/" + r.data.keyword + ".html", !1) : (window.location = baseUrl + "search/" + r.data.keyword + ".html", !1)
            } else popup.msg(r.message), crawl.initShow();
            crawl.keywordSuccess()
        }
    })
}, crawl.searchTop = function (e) {
    var e = $("input[name=searchTop]").val().trim(), a = $("input[name=portal]").val();
    if (a) switch (a.trim()) {
        case"ebay":
            a = "ebay";
            break;
        case"amazon":
            a = "amazon";
            break;
        default:
            a = "ebay"
    }
    if (ValidURL(e)) {
        var t = {url: e}, r = jQuery.param(t);
        if (e.length > 4) return void ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: e},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (e) {
                e.success ? window.location.href = e.data.url : (console.log("paste-link"), window.location.href = baseUrl + "paste-link-result.html?" + r)
            }
        })
    }
    ajax({
        service: "weshop/service/crawl/input.json",
        data: {keyword: e},
        contentType: "json",
        type: "post",
        loading: !1,
        done: function (t) {
            if (t.success) {
                if (0 === e.indexOf("http://") || 0 === e.indexOf("https://")) t.data.cart && (clearTimeout(crawl.redirect), crawl.redirect = setTimeout(function () {
                    location.href = baseSearchUrl + urlcomponent.cart()
                }, 100)); else if (!t.data.cart) {
                    if ("amazon" == a) return window.location = baseSearchUrl + "amazon/search/" + t.data.keyword + ".html", !1;
                    window.location = baseSearchUrl + "search/" + t.data.keyword + ".html?ref=ebay"
                }
            } else popup.msg(t.message), crawl.initShow();
            crawl.keywordSuccess()
        }
    })
}, crawl.initShow = function () {
    $(".search-placeholder").css("display", "block"), $(".ws-header-search").css("display", "block"), $(".ws-header-loading").css("display", "none"), $(".ws-header-brand").css("display", "none"), $(".ws-header-success").css("display", "none"), $(".ws-header-close").css("display", "none"), $(".ws-header-checkout").css("display", "none"), $(".header-search-global").removeClass("search-result"), $("input[name=search]").val(""), $("input[name=searchTop]").val("")
}, crawl.searchSuccess = function () {
    $(".ws-header-brand").css("display", "block"), $(".ws-header-success").css("display", "block"), $(".ws-header-checkout").css("display", "block"), $(".ws-header-loading").css("display", "none"), $(".ws-header-close").css("display", "none"), $(".header-search-global").addClass("search-result")
}, crawl.keywordSuccess = function () {
    $(".ws-header-search").css("display", "block"), $(".ws-header-checkout").css("display", "none"), $(".ws-header-loading").css("display", "none"), $(".ws-header-close").css("display", "none"), $(".ws-header-success").css("display", "none"), $(".header-search-global").removeClass("search-result")
}, crawl.loading = function (e) {
    1 == e ? ($(".ws-header-loading").css("display", "block"), $(".ws-header-close").css("display", "block"), $(".ws-header-checkout").css("display", "none"), $(".ws-header-brand").css("display", "none"), $(".ws-header-success").css("display", "none"), $(".ws-header-search").css("display", "none")) : ($(".ws-header-loading").css("display", "none"), $(".ws-header-close").css("display", "none"), $(".ws-header-search").css("display", "block"))
}, crawl.actionImport = function (e) {
    $(".footer-top").hide(), $(".home-commit").hide(), $(".home-logo").addClass("import-logo"), $(".bg-search").addClass("import-search"), $("a[data-view=domain]").text(e), "www.hm.com" == e ? $("a[data-view=domain]").attr("href", "http://" + e + "/us") : "www.crocs.com" == e ? $("a[data-view=domain]").attr("href", "http://" + e + "/on/demandware.store/Sites-crocs_us-Site/default/Default-Start?country=us-us") : $("a[data-view=domain]").attr("href", "http://" + e), $("a[data-view=domain]").attr("target", "_blank"), $("img[rel=image]").attr("alt", e), $("img[rel=image]").attr("src", $('div[data-site="' + e + '"]').attr("data-image")), $(".import-intro2").show()
}, crawl.calPrice();