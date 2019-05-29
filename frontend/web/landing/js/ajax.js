!function () {
    this.ajax = function (t) {
        t.loading = !(void 0 !== t.loading && !t.loading), "json" === t.contentType && (t.contentType = "application/json; charset=utf-8", t.data = JSON.stringify(t.data)), baseUrl = window.location.origin + "/", (t = $.extend({
            url: baseUrl + t.service,
            dataType: "json",
            headers: {auth: "weshopglobal2017", code: "global2017"},
            success: function (a) {
                t.loading && loading.hide(), t.done(a)
            },
            error: function () {
                t.loading && loading.hide()
            }
        }, t)).loading && loading.show(), setTimeout(function () {
            $.ajax(t)
        }, 300)
    }, this.ajaxSubmit = function (t) {
        var a = {};
        $("#" + t.id).find("input, select, textarea").each(function () {
            "checkbox" === $(this).attr("type") ? $(this).is(":checked") ? a[$(this).attr("name")] = !0 : a[$(this).attr("name")] = !1 : "radio" === $(this).attr("type") ? $(this).is(":checked") && (a[$(this).attr("name")] = $(this).val()) : "" !== $(this).val() && void 0 !== $(this).attr("name") && (isNaN($(this).val()) ? a[$(this).attr("name")] = $(this).val() : a[$(this).attr("name")] = parseFloat($(this).val()))
        }), para = $.extend({
            success: function (a) {
                loading.hide(), a.success ? t.done(a) : (a.data && $("#" + t.id + " input,#" + t.id + " select,#" + t.id + " textarea").each(function () {
                    $(this).parents(".form-group").removeClass("has-error"), $(this).next(".help-block").remove(), $(this).attr("name") && a.data[$(this).attr("name")] && $(this).parents(".form-group").addClass("has-error")
                }), a.message && t.done(a))
            }, service: t.service, type: "post", data: a, contentType: "json"
        }, t), ajax(para)
    }
}();