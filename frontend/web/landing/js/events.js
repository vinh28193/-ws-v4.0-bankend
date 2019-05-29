var events = {
    checkOrder: function () {
        alert("ok")
    }, setLanguage: function (a) {
        isNaN(a) || a < 1 || ajax({
            service: "weshop/service/language/index",
            data: {languageId: a},
            loading: !1,
            done: function (a) {
                a.success, location.reload()
            }
        })
    }, setOptionLanguage: function () {
        var a = parseInt($("select[name=languageOptions]").val());
        isNaN(a) || a < 1 || ajax({
            service: "weshop/service/language/index",
            data: {languageId: a},
            loading: !1,
            done: function (a) {
                a.success, location.reload()
            }
        })
    }
};