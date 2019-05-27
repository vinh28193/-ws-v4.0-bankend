function isEmail(s) {
    return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(s)
}

function isPhoneNumber(s) {
    return /^[0-9]{10,11}$/.test(s)
}

$.urlParam = function (s) {
    var e = new RegExp("[?&]" + s + "=([^&#]*)").exec(window.location.href);
    return null === e ? null : e[1] || 0;
};
var user = {};
$(document).keypress(function (s) {
    var loginModal = $('div#login');
    if (loginModal.length && loginModal.data('show.bs.modal')) {
        13 == s.which && user.login();
    }
});
user.message = function (s) {
    $("#messLabel").html(s), $("#mess").modal()
}, user.showWood = function () {
    $(".dropdown").removeClass("open"), $(".submenu-bg").hide(), $("html").css("overflow-y", "auto"), $("#wood-alert").modal()
};
user.forgot = function () {
    var s = $("#email");
    "" != s.val() ? ($('div[for="email"]').removeClass("has-error"), $.ajax({
        url: baseUrl + "weshop/service/login/forgot.json",
        method: "POST",
        data: {email: s.val()},
        dataType: "json",
        success: function (s) {
            s.success ? ($("#forgot").removeClass("fade").modal("hide"), popup.msg(s.message)) : popup.msg(s.message)
        }
    })) : $('div[for="email"]').addClass("has-error")
};
user.setNewPass = function () {
    var s = $("#newPassword"), e = $("#uid"), a = $("#reNewPassword"), r = $("#token");
    return s.val().length < 6 ? ($('div[for="newPassword"]').addClass("has-has-error"), void s.focus()) : ($('div[for="newPassword"]').removeClass("has-has-error"), s.val() != a.val() ? ($('div[for="reNewPassword"]').addClass("has-has-error"), void a.focus()) : ($('div[for="reNewPassword"]').removeClass("has-has-error"), void $.ajax({
        url: baseUrl + "weshop/service/login/newpass.json",
        method: "POST",
        data: {userId: e.val(), password: s.val(), token: r.val()},
        dataType: "json",
        success: function (s) {
            s.success ? (popup.msg(s.message), window.location.href = baseUrl) : popup.msg(s.message)
        }
    })));
};
user.login = function () {
    null == $.urlParam("ref") ? baseUrl : $.urlParam("ref");
    var s = $("#username"), e = $("#password"), a = window.location.origin;
    $("#login_username_error").hide(), $("#login_password_error").hide();
    var r = $("#remember").is(":checked");
    return "" == s.val() ? ($('div[for="username"]').addClass("has-error"), void s.focus()) : ($('div[for="username"]').removeClass("has-error"), "" == e.val() ? ($('div[for="password"]').addClass("has-error"), void e.focus()) : ($('div[for="password"]').removeClass("has-error"), void $.ajax({
        url: a + "/weshop/auth/login",
        method: "POST",
        data: {username: s.val(), password: e.val(), remember: r},
        loading: !0,
        dataType: "json",
        success: function (e) {
            e.success ? 0 == e.data.status && ("" != e.data.url ? $(window).attr("location", e.data.url) : location.reload()) : (2 == e.data.status || 3 == e.data.status ? ($("#sign-in").removeClass("fade").modal("hide"), popup.msg(e.message)) : (0 == e.data.username && ($('div[for="username"]').addClass("has-error"), $("#login_username_error").show()), 0 == e.data.password && ($('div[for="password"]').addClass("has-error"), $("#login_password_error").show())), s.focus())
        }
    })))
};
user.register = function () {
    null == $.urlParam("ref") ? baseUrl : $.urlParam("ref");
    var s = $("#reusername"), e = $("#rephonenumber"), a = $("#reemail"), r = $("#repassword"),
        o = $("#rerepassword");
    return "" == $("input[name=reusername]").val() ? (s.addClass("has-error"), void $("input[name=reusername]").focus()) : (s.removeClass("has-error"), "" == $("input[name=rephonenumber]").val() ? (e.addClass("has-error"), void $("input[name=rephonenumber]").focus()) : (e.removeClass("has-error"), "" == $("input[name=reemail]").val() ? (a.addClass("has-error"), void $("input[name=reemail]").focus()) : (a.removeClass("has-error"), "" == $("input[name=repassword]").val() ? (r.addClass("has-error"), void $("input[name=repassword]").focus()) : (r.removeClass("has-error"), $("input[name=repassword]").val() != $("input[name=rerepassword]").val() ? (o.addClass("has-error"), void $("input[name=rerepassword]").focus()) : (o.removeClass("has-error"), void $.ajax({
        url: baseUrl + "weshop/auth/register",
        method: "POST",
        data: {
            username: $("input[name=reusername]").val(),
            phone: $("input[name=rephonenumber]").val(),
            email: $("input[name=reemail]").val(),
            password: $("input[name=repassword]").val()
        },
        dataType: "json",
        success: function (s) {
            s.success ? ($("div[for=reError]").css("display", "none"), $("#register").removeClass("fade").modal("hide"), popup.msg(s.message)) : ($("div[for=reError]").html(s.message), $("div[for=reError]").css("display", "block"))
        }
    }))))))
};
user.usRegister = function (s) {
    var e = $("#ususername"), a = $("#usphone"), r = $("#usemail"), o = $("#uspassword"), n = $("#usrepassword");
    return $("input[name=ususername]").val() == "" ? (e.addClass("has-error"), void $("input[name=ususername]").focus()) : (e.removeClass("has-error"), "" == $("input[name=usphone]").val() ? (a.addClass("has-error"), void $("input[name=usphone]").focus()) : (a.removeClass("has-error"), "" == $("input[name=usemail]").val() ? (r.addClass("has-error"), void $("input[name=usemail]").focus()) : (r.removeClass("has-error"), "" == $("input[name=uspassword]").val() ? (o.addClass("has-error"), void $("input[name=uspassword]").focus()) : (o.removeClass("has-error"), $("input[name=uspassword]").val() != $("input[name=usrepassword]").val() ? (n.addClass("has-error"), void $("input[name=usrepassword]").focus()) : (n.removeClass("has-error"), void $.ajax({
        url: baseUrl + "weshop/service/login/register.json",
        method: "POST",
        data: {
            username: $("input[name=ususername]").val(),
            phone: $("input[name=usphone]").val(),
            email: $("input[name=usemail]").val(),
            password: $("input[name=uspassword]").val(),
            type: s
        },
        dataType: "json",
        success: function (s) {
            s.success, popup.msg(s.message)
        }
    }))))))
};
user.close = function () {
    $(".bs-signin-modal-sm").removeClass("fade").modal("hide")
};
user.loadAutionTime = function () {
    $("p.time-leftbh-auction").each(function () {
        var e = parseInt($(this).attr("data")), a = Math.floor((e - new Date) / 1e3);
        a < 0 && (a = 0), 0 != a ? (d = Math.floor(a / 86400), a -= 86400 * d, h = Math.floor(a / 3600), a -= 3600 * h, m = Math.floor(a / 60), a -= 60 * m, s = a, $(this).html("Còn lại " + d + " ngày,<span> " + h + ":" + m + ":" + s + "</span>")) : $(this).html("Hết hạn")
    })
};
user.removeFollowInBackend = function (s) {
    $.ajax({
        url: baseUrl + "weshop/service/customer/follow.json",
        method: "POST",
        data: {itemId: s, type: 1, status: 1},
        dataType: "json",
        success: function (e) {
            0 == e.success && $('tr[forItem="' + s + '"]').hide()
        }
    })
};
user.click = function () {
    $.ajax({
        url: baseUrl + "weshop/user/statistical", method: "GET", success: function (s) {
            s ? ($("#countWallet").html("<b>" + s.wallets + "</b>"), $("#countAuctions").html("<b>" + s.autions + "</b>"), $("#countOrder").html("<b>" + s.coupons + "</b>"), $("#countClaim").html("<b>" + s.claims + "</b>"), $("#countWarehouse").html("<b>" + s.warehouses + "</b>"), $("#countShipmentBulk").html("<b>" + s.shipment_bulks + "</b>"), $("#countInvoice").html("<b>" + s.invoices + "</b>"), $("#countCoupon").html("<b>" + s.coupons + "</b>"), $("#shippingPackage").html("<b>" + s.orderShip + "</b>")) : popup.msg("Fail statistical")
        }
    })
};
