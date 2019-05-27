var order = {};
order.steepOne = function () {
    ajax({
        service: "weshop/service/order/save", loading: !0, done: function (e) {
            e.success ? e.data.binCode ? location.href = baseUrl + urlcomponent.order_steep_two(e.data.binCode) : location.href = baseUrl + urlcomponent.order_steep_two(e.data.id) : popup.msg(e.message)
        }
    })
}, order.quotes2 = function () {
    var e = $("input[name=email]").val(), o = $("input[name=name]").val(), n = $("#link").val();
    if (ValidURL(n)) {
        var a = {url: n};
        jQuery.param(a);
        n.length > 4 && ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: n},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (n) {
                n.success ? window.location.href = n.data.url : ajaxSubmit({
                    service: "weshop/service/order/quotes",
                    data: {link: $("#link").val(), phone: $("input[name=phone]").val(), email: e, name: o},
                    loading: !0,
                    type: "post",
                    done: function (e) {
                        e.success ? ($("input[name=link]").val(""), popup.msg(e.message)) : (popup.msg(e.message), $("html, body").animate({scrollTop: 300}, 700))
                    }
                })
            }
        })
    }
}, order.quotesvn = function () {
    $("input[name=email]").val();
    var e = $("input[name=name]").val();
    ajaxSubmit({
        service: "weshop/service/order/quotes",
        data: {link: $("#link2").val(), phone: $("input[name=phone2]").val(), email: $("#email2").val(), name: e},
        loading: !0,
        type: "post",
        done: function (e) {
            e.success ? ($("input[name=link2]").val(""), popup.msg(e.message)) : (popup.msg(e.message), $("html, body").animate({scrollTop: 300}, 700))
        }
    })
}, order.quotesvn1 = function () {
    $("input[name=email]").val();
    var e = $("input[name=name]").val(), o = $("#link2").val();
    if (ValidURL(o)) {
        var n = {url: o};
        jQuery.param(n);
        o.length > 4 && ajax({
            service: "weshop/service/quotes/detecturl.json",
            data: {keyword: o},
            contentType: "json",
            type: "post",
            loading: !1,
            done: function (o) {
                o.success ? window.location.href = o.data.url : ajaxSubmit({
                    service: "weshop/service/order/quotes",
                    data: {
                        link: $("#link2").val(),
                        phone: $("input[name=phone2]").val(),
                        email: $("#email2").val(),
                        name: e
                    },
                    loading: !0,
                    type: "post",
                    done: function (e) {
                        e.success ? ($("input[name=link2]").val(""), popup.msg(e.message)) : (popup.msg(e.message), $("html, body").animate({scrollTop: 300}, 700))
                    }
                })
            }
        })
    }
}, order.quotes = function () {
    var e = $("input[name=link]").val(), o = $("input[name=link]").val();
    if (e) {
        if (ValidURL(o)) {
            var n = {url: o};
            jQuery.param(n);
            o.length > 4 && ajax({
                service: "weshop/service/quotes/detecturl.json",
                data: {keyword: o},
                contentType: "json",
                type: "post",
                loading: !1,
                done: function (o) {
                    o.success ? window.location.href = o.data.url : location.href = baseUrl + urlcomponent.quotes() + "?link=" + e
                }
            })
        }
    } else popup.msg("You must enter a product link want a quote!")
}, order.coupon = function (e) {
    if (!$("input[name=couponCode]").val()) return e ? ($("#step3-errorcoupon").css("display", "block"), $("#step3-errorcoupon-msg").html("You must enter the coupon code!")) : popup.msg("You must enter the coupon code!"), !1;
    ajaxSubmit({
        service: "weshop/service/order/coupon", id: "form-add", loading: !0, done: function (o) {
            o.success ? ($("#discount-coupon").html(o.data.html), $("#price-discount").html(o.data.totalFinal), $("#discount-nocoupon").css("display", "none"), e ? ($("#price-discount-2").html(o.data.discount), $(".ck-order-discount").css("display", "block"), $("#price-final").html(o.data.totalFinal), $("#btnCheckout").attr("onclick", "payment.step3id()")) : $("#btnCheckout").attr("onclick", "payment.paymentid()")) : e ? ($("#step3-errorcoupon").css("display", "block"), $("#step3-errorcoupon-msg").html(o.message)) : popup.msg(o.message)
        }
    })
}, order.removecoupon = function (e, o) {
    ajax({
        service: "weshop/service/order/removecoupon", data: {id: e}, loading: !0, done: function (e) {
            e.success ? ($("#discount-nocoupon").css("display", "block"), $("#discount-coupon").html(e.data.html), $("#price-discount").html(e.data.totalFinal), o ? ($(".ck-order-discount").css("display", "none"), $("#price-final").html(e.data.totalFinal), $("#btnCheckout").attr("onclick", "payment.step3id()")) : $("#btnCheckout").attr("onclick", "payment.paymentid()")) : popup.msg(e.message)
        }
    })
}, order.walletbuck = function () {
    var e = $("input[name=TotalWalletPaidPromotionAmountFee]").val();
    if (console.log(e), !e) return popup.msg("You must enter the buck!");
    ajaxSubmit({
        service: "weshop/service/order/buck", id: "form-add", loading: !0, done: function (e) {
            e.success ? ($("#discount-buck").html('<div class="left">Redeem bằng tiền thưởng:<p class="small"></p></div><div class="right">-' + e.data.promoBuck + "</div>"), $("#price-discount").html(e.data.totalFinal), $("#discount-nobuck").css("display", "none")) : popup.msg(e.message)
        }
    })
}, order.createOrderBuyNow = function () {
    $("#form-order-buy-now").submit()
}, order.deleteItem = function (e, o) {
    ajax({
        service: "weshop/service/order/deleteitem", data: {binCode: e, id: o}, loading: !0, done: function (e) {
            e.success ? ($("#discount-coupon").html(e.data.html), $("#price-amount").html(e.data.totalAmount), $("#price-discount").html(e.data.totalFinal), $("#order-item-" + o).remove(), order.reset()) : popup.msg(e.message)
        }
    })
}, order.reset = function () {
    $("#payment-step2").children().length < 2 && $(".bot-itcart").css("display", "none")
}, order.checkOrderMain = function () {
    var e = $("input[name=checkMainOrderId]").val();
    "" != e ? $.ajax({
        url: baseUrl + "weshop/service/login/checkorder.json",
        method: "POST",
        data: {order: e},
        dataType: "json",
        loading: !0,
        success: function (o) {
            o.success ? ($("#msg-checkordermain-error").css("display", "none"), window.location = baseUrl + urlcomponent.tracking_order(e)) : ($("#msg-checkordermain-error").html(o.message), $("#msg-checkordermain-error").css("display", "block"))
        }
    }) : $("#msg-checkordermain-error").css("display", "block")
}, order.checkOrderLeft = function () {
    var e = $("input[name=checkLeftOrderMail]").val(), o = $("input[name=checkLeftOrderId]").val();
    "" == o && ($("#msg-checkorderleft-error").html("Please enter the code order."), $("#msg-checkorderleft-error").css("display", "block")), $.ajax({
        url: baseUrl + "weshop/service/login/checkorder.json",
        method: "POST",
        data: {mail: e, order: o},
        loading: !0,
        dataType: "json",
        success: function (e) {
            e.success ? ($("#msg-checkorderleft-error").css("display", "none"), window.location = baseUrl + urlcomponent.tracking_order(o)) : ($("#msg-checkorderleft-error").html(e.message), $("#msg-checkorderleft-error").css("display", "block"))
        }
    })
}, order.trackingOrderItem = function (e) {
    $.ajax({
        url: baseUrl + "weshop/service/login/checkorderitem.json",
        method: "POST",
        data: {orderItemId: e},
        loading: !0,
        dataType: "json",
        success: function (e) {
            e.success && ($("#tracking-content").html(e.data.html), $("#track-item").modal("show").addClass("fade"))
        }
    })
}, order.pastelink = function () {
    var e = $("input[name=pastelink]").val();
    e ? location.href = baseUrl + "other-paste-link.html?link=" + e : popup.msg("You must enter a product link want a quote!")
}, order.pastelink1 = function () {
    var e = $("input[name=pastelink1]").val();
    e ? location.href = baseUrl + "other-paste-link.html?link=" + e : popup.msg("You must enter a product link want a quote!")
};