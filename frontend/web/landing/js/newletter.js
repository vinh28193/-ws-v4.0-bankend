newletter = {}, newletter.saveEmail = function () {
    ajax({
        service: "weshop/service/newletter/index",
        loading: !0,
        data: {email: $("#newsletter").val()},
        success: function (e) {
            popup.msg(e.message), $(".loading_new").hide()
        }
    })
}, newletter.emailBlackfriday = function () {
    ajax({
        service: "weshop/service/newletter/blackfriday",
        loading: !0,
        data: {email: $("#emailBf").val()},
        success: function (e) {
            $(".modal-popup-v2").hide(), $(".modal-bg-popup-v2").hide(), popup.msg(e.message), $(".loading_new").hide()
        }
    })
};