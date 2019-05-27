fly = function (a) {
    a = $.extend(a), fly.baseUrl = a.baseUrl, fly.assetsUrl = a.assetsUrl, fly.uploadUrl = a.uploadUrl, fly.templatePath = a.templatePath, fly.servicePath = a.servicePath
}, fly.navigate = function (a) {
    params = $(location).attr("hash").replace("#", "").split("/"), params.length <= 1 && "" == params[0] && (params = a.split("/")), fly.navigate.action(params), $(window).bind("hashchange", function () {
        params = $(location).attr("hash").replace("#", "").split("/"), params.length <= 1 && "" == params[0] && (params = a.split("/")), fly.navigate.action(params)
    })
}, fly.navigate.action = function (a) {
    for (var e = a.length; e > 0; e--) {
        try {
            var t = window[a[0]]
        } catch (a) {
        }
        for (var s = 1; s < e; s++) try {
            t = t[a[s]]
        } catch (a) {
        }
        try {
            t(a);
            break
        } catch (a) {
            console.log(a), document.location = "#"
        }
    }
}, fly.template = function (a, e) {
    return new EJS({url: fly.assetsUrl + fly.templatePath + a}).render(e)
}, fly.ajax = function (a) {
    (a = $.extend({
        method: "get",
        loading: !1,
        async: !0
    }, a)).loading && loading.show(), $.ajax({
        url: fly.baseUrl + fly.servicePath + a.service,
        type: a.method,
        data: a.data,
        dataType: "json",
        async: a.async,
        success: function (e) {
            a.loading && loading.hide(), a.success(e)
        },
        error: function () {
            a.loading && loading.hide()
        }
    })
}, fly.submit = function (a) {
    para = {
        data: $("#" + a.id).serialize(), success: function (e) {
            e.status ? a.success(e) : ($("#" + a.id + " input, select, textarea").each(function () {
                $(this).removeClass("error"), $(this).next(".errorMessage").remove(), $(this).attr("name") && e.data[$(this).attr("name").replace(/.*\[/, "").replace(/\].*/, "")] && ($(this).addClass("error"), $(this).after('<div class="errorMessage">' + e.data[$(this).attr("name").replace(/.*\[/, "").replace(/\].*/, "")] + "</div>"))
            }), e.message && popup.msg(e.message), popup.resetPos())
        }, service: a.service, method: "post", loading: a.loading
    }, fly.ajax(para)
}, fly.submitWithFile = function (a) {
    var e = fly.baseUrl + fly.servicePath + a.service;
    $("#upload-iframe-submit").length || $("body").append('<iframe id="upload-iframe-submit" name="upload-iframe-submit" style="display:none" />'), $("#" + a.id).attr("target", "upload-iframe-submit"), $("#" + a.id).attr("action", e), $("#" + a.id).attr("method", "post"), $("#" + a.id).attr("enctype", "multipart/form-data"), $("#" + a.id).submit(), $("#upload-iframe-submit").load(function () {
        try {
            var e = $("#upload-iframe-submit").contents().find("body");
            e = $.parseJSON(e.text()), a.loading && loading.hide(), e.status ? a.success(e) : ($("#" + a.id + " input, select, textarea").each(function () {
                $(this).removeClass("error"), $(this).next(".errorMessage").remove(), $(this).attr("name") && e.data[$(this).attr("name").replace(/.*\[/, "").replace(/\].*/, "")] && ($(this).addClass("error"), $(this).after('<div class="errorMessage">' + e.data[$(this).attr("name").replace(/.*\[/, "").replace(/\].*/, "")] + "</div>"))
            }), e.message && popup.msg(e.message), popup.resetPos())
        } catch (e) {
            a.loading && loading.hide(), popup.msg("Có lỗi xảy ra trong quá trình truyền dữ liệu, xin hãy kiểm tra lại kết nối mạng!" + e)
        }
    })
}, fly.parseTime = function (a) {
    var e = new Date(parseInt(1e3 * a));
    return e.getDate() + "/" + (e.getMonth() + 1) + "/" + e.getFullYear() + " " + e.getHours() + ":" + e.getMinutes() + ":" + e.getSeconds()
};