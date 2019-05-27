var app = {};
$(".price-request2").click(function () {
    $(this).addClass("close"), $(".price-now").css("right", "0")
}), $(document).mouseup(function (e) {
    var t = $(".price-now");
    t.is(e.target) || 0 !== t.has(e.target).length || ($(".price-request2").removeClass("close"), $(".price-now").css("right", "-300px"))
}), app.trackingorder = function () {
    var e = $('input[name="tracking_bin"]').val();
    var code= e;
    ajax({
        service: "weshop/service/app/trackingorder",
        type: "GET",
        data: {trackingPrivateKey: e},
        loading: !0,
        done: function (e) {
            if( 1 == e.success ) {
                window.location.href = baseUrl + 'tracking/' + e.data + '.html';
            }else{
                 window.location.href = baseUrl + 'bin404.html';

            }
        },
    });
}, app.quote = function (e) {
    value = {}, data = $("#" + e).serializeArray().map(function (e) {
        value[e.name] = e.value
    }), console.log(value), ajax({
        service: "weshop/service/order/quotes",
        data: value,
        loading: !0,
        type: "POST",
        done: function (t) {
            t.success ? ($("#" + e)[0].reset(), popup.msg(t.message)) : (popup.msg(t.message), $("html, body").animate({scrollTop: 300}, 700))
        }
    })
}, app.followItem = function (e, t) {
    ajax({
        service: "weshop/service/follow/store", type: "POST", data: {itemId: e, source: t}, done: function (e) {
            1 == e.success ? 1 === e.data ? (popup.msg(e.message), $("#followItem i").removeClass("favorite-ico_active").addClass("favorite-ico")) : (popup.msg(e.message), $("#followItem i").removeClass("favorite-ico").addClass("favorite-ico_active")) : popup.msg(e.message)
        }
    })
}, app.openQuote = function () {
    $(".price-request2").addClass("close"), $(".price-now").css("right", 0), $(location).attr("href");
    window.location.pathname;
    var e = window.location;
    $("#link2").val(e), $("#link2").attr("disabled", "disabled")
}, app.searchNew = function (e, t) {
    var a = $(e).val();
    if ("" == a){ return !1 ; }
    if (/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(a)) {
        var s = "undefined", o = "undefined",
            i = /(ftp|http|https):\/\/www.ebay.com\/itm\/([\S]*\/[0-9]*(\S+)|[0-9]([\/]*\S+))?/;
        if (/(ftp|http|https):\/\/www.amazon.com\/(gp\/product|[0-9a-zA-Z\-]*\/dp|dp)(\S+)?/.test(a)) s = /(ftp|http|https):\/\/www.amazon.com\/dp(\S+)?/.test(a) ? (s = a.split("/")[4]).split("?")[0] : (s = a.split("/")[5]).split("?")[0], o = "amazon"; else if (i.test(a)) s = /(ftp|http|https):\/\/www.ebay.com\/itm\/[\S]*\/[0-9]*(\S+)?/.test(a) ? (s = a.split("/")[5]).split("?")[0] : (s = a.split("/")[4]).split("?")[0], o = "ebay"; else {
            var p = "Your link is not valid.";
            p += "</br>", p += "Only support the link of product detail (Ebay or Amazon),", p += " try another or search with keyword !", popup.msg(p), $(e).val("")
        }
        "undefined" !== s && ajax({
            service: "/weshop/service/search-detail/search",
            type: "POST",
            data: {sku: s, type: o},
            done: function (t) {
                if (t.success) return window.location.href = t.data, !1;
                popup.msg(t.message), $(e).val("")
            }
        })
    } else {

        a = (a = a.replace(/\/+/g, " ")).replace(/[\%]+/g, ""), a = encodeURIComponent(a);
        var r = baseUrl;
        r += null === t || "undefined" === t || "" === t ? "search/" + a + ".html?portal=ebay" : t + "/search/" + a + ".html";
        if(t === 'amazon-uk'){
            var arbn = $('select[name="rootBrowseNode"]').val();
            if(arbn !== null || typeof arbn !== "undefined"){
                if(arbn === '') { arbn = 'all';}
                var pos = r.indexOf('?');
                if(pos > 0){
                    r += '&arbn='+ arbn;
                }else {
                    r += '?arbn='+ arbn;
                }
            }
        }

        window.location.href = r;
    }
};