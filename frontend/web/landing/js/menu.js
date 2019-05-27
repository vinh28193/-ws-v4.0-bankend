var menu = {};
menu.slideGlobal = function () {
    for ($numberSlider = $("input[name=menu-global-slide]").val(), $number = 1; $number <= $numberSlider; $number++) menu.runSlideGlobal("globe-sub-slider-" + $number)
}, menu.runSlideGlobal = function (e) {
    $(".keep-navbar .cate-nav ul li.globe-sub .sub-menu li a").mouseenter(function () {
        var a = $("#" + e).data("owlCarousel");
        a._width = $("#" + e).width(), a.invalidate("width"), a.refresh()
    }), $("#" + e).owlCarousel({slideSpeed: 300, paginationSpeed: 400, loop: !0, autoplay: 1e3, items: 4, margin: 10})
}, setInterval(function () {
    $(".time-countdown-menu").each(function () {
        var e = parseInt($(this).attr("data")), a = Math.floor((1e3 * e - new Date) / 1e3);
        a < 0 && (a = 0), 0 != a ? (d = Math.floor(a / 86400), a -= 86400 * d, h = Math.floor(a / 3600), a -= 3600 * h, m = Math.floor(a / 60), a -= 60 * m, s = a, $(this).html(+d + "d " + h + "h " + m + "m " + s + "s")) : $(this).html("expired")
    })
}, 1e3)
    ,  $(".keep-navbar .cate-nav ul li.globe-sub").mouseenter(function () {
    $(".keep-navbar").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").addClass("open")
}), $(".keep-navbar .cate-nav ul li.globe-sub").mouseleave(function () {
    $(".keep-navbar").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").removeClass("open")
}), $(".keep-navbar.amazon .cate-nav ul li.globe-sub").mouseenter(function () {
    $(".keep-navbar.amazon").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").addClass("open")
}), $(".keep-navbar.amazon .cate-nav ul li.globe-sub").mouseleave(function () {
    $(".keep-navbar.amazon").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").removeClass("open")
}), $(".keep-navbar.ebay .cate-nav ul li.globe-sub").mouseenter(function () {
    $(".keep-navbar.ebay").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").addClass("open")
}), $(".keep-navbar.ebay .cate-nav ul li.globe-sub").mouseleave(function () {
    $(".keep-navbar.ebat").hasClass("fixed") && $(".keep-navbar .cate-nav ul li.globe-sub").removeClass("open")
}), $(".keep-navbar .search-box .form-group .input-group-btn:first-child > .btn").on("show.bs.dropdown", function (e) {
    $(".submenu-bg").show()
}), $(".keep-navbar .search-box .form-group .input-group-btn:first-child > .btn").on("hide.bs.dropdown", function (e) {
    $(".submenu-bg").hide()
}), $(".navbar-default.navbar-2 ul li.dropdown .dropdown-toggle").removeAttr("data-toggle"), $(".navbar-default.navbar-2 ul li.dropdown").mouseenter(function () {
    $(this).addClass("open"), $(".submenu-bg").show(), $(this).parent().click()
}), $(".navbar-default.navbar-2 ul li.dropdown").mouseleave(function () {
    $(this).removeClass("open"), $(".submenu-bg").hide()
}), $(".navbar-default.navbar-2 ul li.active").mouseenter(function () {
    $(".submenu-bg").hide()
});