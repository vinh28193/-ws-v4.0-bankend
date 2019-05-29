$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip(), $("#ld-deal-slider").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    }), $("#rate-slider").owlCarousel({
        animateOut: "fadeOut",
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    }), $("#home-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        items: 1,
        nav: !0,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1,
        autoplay: 1e3
    });
    var e = $("#home-slider").data("owlCarousel");
    $(".homeSlide .next").click(function (t) {
        t.preventDefault(), e.next()
    }), $(".homeSlide .prev").click(function (t) {
        t.preventDefault(), e.prev()
    }), $("#ld-deal-1").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var t = $("#ld-deal-1").data("owlCarousel");
    $(".deal-box.featured .next").click(function (e) {
        e.preventDefault(), t.next()
    }), $(".deal-box.featured .prev").click(function (e) {
        e.preventDefault(), t.prev()
    }), $("#ld-deal-2").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var i = $("#ld-deal-2").data("owlCarousel");
    $(".deal-box.fashion .next").click(function (e) {
        e.preventDefault(), i.next()
    }), $(".deal-box.fashion .prev").click(function (e) {
        e.preventDefault(), i.prev()
    }), $("#ld-deal-3").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var a = $("#ld-deal-3").data("owlCarousel");
    $(".deal-box.electronic .next").click(function (e) {
        e.preventDefault(), a.next()
    }), $(".deal-box.electronic .prev").click(function (e) {
        e.preventDefault(), a.prev()
    }), $("#ld-deal-4").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var o = $("#ld-deal-4").data("owlCarousel");
    $(".deal-box.fashion.art .next").click(function (e) {
        e.preventDefault(), o.next()
    }), $(".deal-box.fashion.art .prev").click(function (e) {
        e.preventDefault(), o.prev()
    }), $("#ld-deal-5").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var l = $("#ld-deal-5").data("owlCarousel");
    $(".deal-box.home .next").click(function (e) {
        e.preventDefault(), l.next()
    }), $(".deal-box.home .prev").click(function (e) {
        e.preventDefault(), l.prev()
    }), $("#ld-deal-6").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var n = $("#ld-deal-6").data("owlCarousel");
    $(".deal-box.electronic.sport .next").click(function (e) {
        e.preventDefault(), n.next()
    }), $(".deal-box.electronic.sport .prev").click(function (e) {
        e.preventDefault(), n.prev()
    }), $("#amz-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1,
        autoplay: 1e3
    });
    var s = $("#amz-slider").data("owlCarousel");
    $(".slider-next").click(function (e) {
        e.preventDefault(), s.next()
    }), $(".slider-prev").click(function (e) {
        e.preventDefault(), s.prev()
    }), $("#landing-slider").owlCarousel({
        loop: !0,
        margin: 0,
        responsiveClass: !1,
        nav: !1,
        dots: !0,
        autoplay: !0,
        autoHeight: !1,
        autoplayTimeout: 4e3,
        autoplayHoverPause: !0,
        navText: !1,
        responsive: {0: {items: 1}, 768: {items: 2}, 1200: {items: 3}}
    }), $("#amz-recently").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 2}, 376: {items: 3}, 640: {items: 5}, 991: {items: 7}}
    });
    var r = $("#amz-recently").data("owlCarousel");
    $(".amz-recently .amz-recently-control .next").click(function (e) {
        e.preventDefault(), r.next()
    }), $(".amz-recently .amz-recently-control .prev").click(function (e) {
        e.preventDefault(), r.prev()
    }), $("#deal-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}}
    });
    var p = $("#deal-slider").data("owlCarousel");
    $(".deal-slider-next").click(function (e) {
        e.preventDefault(), p.next()
    }), $(".deal-slider-prev").click(function (e) {
        e.preventDefault(), p.prev()
    }), $("#buy-slider1").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}, 1199: {items: 5}}
    });
    c = $("#buy-slider1").data("owlCarousel");
    $(".prod-slidebox .prod-slider-control .next1").click(function (e) {
        e.preventDefault(), c.next()
    }), $(".prod-slidebox .prod-slider-control .prev1").click(function (e) {
        e.preventDefault(), c.prev()
    }), $("#buy-slider2").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}, 1199: {items: 5}}
    });
    var d = $("#buy-slider2").data("owlCarousel");
    $(".prod-slidebox .prod-slider-control .next").click(function (e) {
        e.preventDefault(), d.next()
    }), $(".prod-slidebox .prod-slider-control .prev").click(function (e) {
        e.preventDefault(), d.prev()
    }), $("#buy-slider3").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {
            0: {items: 1},
            376: {items: 2},
            640: {items: 5},
            991: {items: 6},
            1199: {items: 8},
            360: {items: 2},
            533: {items: 4},
            767: {items: 6},
            736: {items: 6},
            480: {items: 4},
            414: {items: 3},
            375: {items: 3},
            320: {items: 2}
        }
    });
    var c = $("#buy-slider1").data("owlCarousel");
    $(".prod-slidebox .prod-slider-control .next1").click(function (e) {
        e.preventDefault(), c.next()
    }), $(".prod-slidebox .prod-slider-control .prev1").click(function (e) {
        e.preventDefault(), c.prev()
    }), $(".dhg-customer-view").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 6}}
    });
    var u = $(".dhg-customer-view").data("owlCarousel");
    $(".dhg-customer-view-control .arrow.next").click(function (e) {
        e.preventDefault(), u.next()
    }), $(".dhg-customer-view-control .arrow.prev").click(function (e) {
        e.preventDefault(), u.prev()
    }), $("#selling-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}}
    });
    var m = $("#selling-slider").data("owlCarousel");
    $(".selling-slider-next").click(function (e) {
        e.preventDefault(), m.next()
    }), $(".selling-slider-prev").click(function (e) {
        e.preventDefault(), m.prev()
    }), $("#amz-related-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 6}}
    });
    var v = $("#amz-related-slider").data("owlCarousel");
    $(".amz-related-next").click(function (e) {
        e.preventDefault(), v.next()
    }), $(".amz-related-prev").click(function (e) {
        e.preventDefault(), v.prev()
    }), $("#amz-bought-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 6}}
    });
    var f = $("#amz-bought-slider").data("owlCarousel");
    $(".amz-bought-next").click(function (e) {
        e.preventDefault(), f.next()
    }), $(".amz-bought-prev").click(function (e) {
        e.preventDefault(), f.prev()
    }), $("#camera-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}}
    });
    var b = $("#camera-slider").data("owlCarousel");
    $(".camera-slider-next").click(function (e) {
        e.preventDefault(), b.next()
    }), $(".camera-slider-prev").click(function (e) {
        e.preventDefault(), b.prev()
    }), $("#gift-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}}
    });
    var h = $("#gift-slider").data("owlCarousel");
    $(".gift-slider-next").click(function (e) {
        e.preventDefault(), h.next()
    }), $(".gift-slider-prev").click(function (e) {
        e.preventDefault(), h.prev()
    }), $("#hdg-viewed-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 3}, 991: {items: 4}}
    });
    var g = $("#hdg-viewed-slider").data("owlCarousel");
    $(".dhg-view-next").click(function (e) {
        e.preventDefault(), g.next()
    }), $(".dhg-view-prev").click(function (e) {
        e.preventDefault(), g.prev()
    }), $("#ebay-slider").owlCarousel({
        navigation: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !0,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var k = $("#ebay-slider").data("owlCarousel");
    $(".ebay-slider-next").click(function (e) {
        e.preventDefault(), k.next()
    }), $(".ebay-slider-prev").click(function (e) {
        e.preventDefault(), k.prev()
    }), $("#dhg-main-slider").owlCarousel({
        navigation: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !0,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var C = $("#dhg-main-slider").data("owlCarousel");
    $(".dhg-arrow.dhg-next").click(function (e) {
        e.preventDefault(), C.next()
    }), $(".dhg-arrow.dhg-prev").click(function (e) {
        e.preventDefault(), C.prev()
    }), $("#Banner-slide").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    }), $("#ldslider").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    $("#Banner-slide").data("owlCarousel");
    $(".Banner-next").click(function (e) {
        e.preventDefault(), lpbanner_slider.next()
    }), $(".Banner-prev").click(function (e) {
        e.preventDefault(), lpbanner_slider.prev()
    }), $("#cart-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 5}}
    });
    var y = $("#cart-slider").data("owlCarousel");
    $(".cart-next").click(function (e) {
        e.preventDefault(), y.next()
    }), $(".cart-prev").click(function (e) {
        e.preventDefault(), y.prev()
    }), $("#ebay-detail-thumb").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 3}, 376: {items: 4}, 640: {items: 4}, 991: {items: 5}}
    });
    var w = $("#ebay-detail-thumb").data("owlCarousel");
    $(".ebay-detail-next").click(function (e) {
        e.preventDefault(), w.next()
    }), $(".ebay-detail-prev").click(function (e) {
        e.preventDefault(), w.prev()
    }), $("#ebay-daily-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 5}}
    });
    var x = $("#ebay-daily-slider").data("owlCarousel");
    $(".ebay-dailydeal .daily-box .ebay-daily-next").click(function (e) {
        e.preventDefault(), x.next()
    }), $(".ebay-dailydeal .daily-box .ebay-daily-prev").click(function (e) {
        e.preventDefault(), x.prev()
    }), $("#logo-slide").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        nav: !0,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 5}}
    });
    $("#logo-slide").data("owlCarousel");
    $(".logo-next").click(function (e) {
        e.preventDefault(), logo, slide.next()
    }), $(".logo-prev").click(function (e) {
        e.preventDefault(), logo, slide.prev()
    }), $("#ebay-watching-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: 1e3,
        responsive: {0: {items: 1}, 376: {items: 2}, 640: {items: 4}, 991: {items: 6}}
    });
    var D = $("#ebay-watching-slider").data("owlCarousel");
    $(".ebay-watching-next").click(function (e) {
        e.preventDefault(), D.next()
    }), $(".ebay-watching-prev").click(function (e) {
        e.preventDefault(), D.prev()
    }), $(".dbn-pro-slide").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: !0,
        responsive: {
            0: {items: 1},
            376: {items: 1},
            640: {items: 1},
            991: {items: 3},
            1199: {items: 5},
            767: {items: 1}
        }
    });
    S = $("#dhg-detail-thumb").data("owlCarousel");
    $(".dhg-detail-next").click(function (e) {
        e.preventDefault(), S.next()
    }), $(".dhg-detail-prev").click(function (e) {
        e.preventDefault(), S.prev()
    }), $(".DBN-pro-slide").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        nav: !0,
        autoplay: !1,
        responsive: {
            0: {items: 1},
            376: {items: 1},
            640: {items: 1},
            767: {items: 1},
            991: {items: 1},
            1199: {items: 4}
        }
    }), $("#dhg-detail-thumb").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        nav: !0,
        autoplay: 1e3,
        responsive: {0: {items: 3}, 376: {items: 4}, 640: {items: 4}, 991: {items: 3}, 1199: {items: 5}}
    });
    var S = $("#dhg-detail-thumb").data("owlCarousel");
    $(".dhg-detail-next").click(function (e) {
        e.preventDefault(), S.next()
    }), $(".dhg-detail-prev").click(function (e) {
        e.preventDefault(), S.prev()
    }), $(".drawer").drawer({
        iscroll: {mouseWheel: !1, preventDefault: !1},
        showOverlay: !0
    }), $(".amazone-navbar .shop-by .dropdown-btn").mouseenter(function () {
        $(".amazone-navbar .shop-by .shopby-drop").show(), $(".home-amazone .bg-opacity").show(), $(".amazone-user-nav > li .dropdown-menu").slideUp(), $(".amazone-user-nav > li > a > i").removeClass("fa-angle-up").addClass("fa-angle-down")
    }), $(".amazone-navbar .shop-by").mouseleave(function () {
        $(".amazone-navbar .shop-by .shopby-drop").hide(), $(".home-amazone .bg-opacity").hide()
    }), $(".footer-info .input-group input").focusin(function () {
        $(".footer-info .input-group .input-group-addon").css("border-color", "#e67425").css("background", "#e67425")
    }), $(".footer-info .input-group input").focusout(function () {
        $(".footer-info .input-group .input-group-addon").css("border-color", "#2796B6").css("background", "#2796B6")
    }), $(".header-search .input-group input").focusin(function () {
        $(".header-search .input-group .input-group-addon").css("border-color", "#e67425").css("background", "#e67425"), $(".header-search .input-group .search-placeholder").css("z-index", "-100")
    }), $(".header-search .input-group input").focusout(function () {
        $(".footer-info .input-group .input-group-addon").css("border-color", "#2796B6").css("background", "#2796B6"), $(".header-search .input-group .search-placeholder").css("z-index", "100")
    }), $(window).scroll(function () {
        $(this).scrollTop() > 500 ? $(".to-top").css("right", "30px") : $(".to-top").css("right", "-100px")
    }), $(".to-top").click(function () {
        $("html, body").animate({scrollTop: $("body").offset().top}, 800)
    }), $(".dhg-cate-dropdown .cate-title").click(function () {
        $(".dhg-cate-dropdown .dhg-cate-list").slideToggle()
    }), $(".video-content").css("padding-top", ($(".video-wrap").height() - $(".video-wrap h1").height()) / 2 + "px"), $("#background-video").backgroundVideo({pauseVideoOnViewLoss: !1}), $(".header-search .input-group input").focusin(function () {
        $(this).parent().find(".input-group-addon").css("border-color", "#e67425").css("background", "#e67425")
    }), $(".header-search .input-group input").focusout(function () {
        $(this).parent().find(".input-group-addon").css("border-color", "#2796B6").css("background", "#2796B6")
    }), $(".footer-info .input-group input").focusin(function () {
        $(".footer-info .input-group .input-group-addon").css("border-color", "#e67425").css("background", "#e67425")
    }), $(".footer-info .input-group input").focusout(function () {
        $(".footer-info .input-group .input-group-addon").css("border-color", "#2796B6").css("background", "#2796B6")
    }), $(".payment-option .nav-tabs > li").click(function () {
        $(".payment-option .nav-tabs > li").hasClass("active") && ($(".payment-option .nav-tabs > li > a > i").removeClass("dot-active").addClass("dot-unactive"), $(this).find("i").removeClass("dot-unactive").addClass("dot-active"))
    }), $(".help-tab-content .tab-hide").click(function () {
        $(".tabbed.round ul li").removeClass("active"), $(".help-tab-content .help-buy").slideUp(), $(".help-tab-content .help-ship").slideUp(), $(".tabbed.round ul li i").removeClass("fa-angle-up").addClass("fa-angle-down"), $(".tabbed").css("border-bottom", "1px solid #000")
    }), $(".tabbed.round ul li").click(function () {
        $(this).hasClass("active") ? ($(this).removeClass("active"), $(this).find("i").removeClass("fa-angle-up").addClass("fa-angle-down"), $(".tabbed").css("border-bottom", "1px solid #000")) : ($(this).addClass("active"), $(this).find("i").removeClass("fa-angle-down").addClass("fa-angle-up"), $(".tabbed").css("border-bottom", "4px solid #2796b6")), $(".tabbed.round ul li.tab-buy").hasClass("active") ? $(".help-tab-content .help-buy").slideDown() : $(".help-tab-content .help-buy").slideUp(), $(".tabbed.round ul li.tab-ship").hasClass("active") ? $(".help-tab-content .help-ship").slideDown() : $(".help-tab-content .help-ship").slideUp(), $("html, body").animate({scrollTop: $("#help-me").offset().top - 50}, 800)
    }), $(".tabbed.round ul li.tab-buy").click(function () {
        $(".tabbed.round ul li.tab-ship").removeClass("active"), $(".help-tab-content .help-ship").slideUp(), $(".tabbed.round ul li.tab-ship").find("i").removeClass("fa-angle-up").addClass("fa-angle-down")
    }), $(".tabbed.round ul li.tab-ship").click(function () {
        $(".tabbed.round ul li.tab-buy").removeClass("active"), $(".help-tab-content .help-buy").slideUp(), $(".tabbed.round ul li.tab-buy").find("i").removeClass("fa-angle-up").addClass("fa-angle-down")
    }), $(".scrolldown").click(function () {
        $("html, body").animate({scrollTop: $("#help-me").offset().top - 50}, 800)
    }), $(".top-nav .navbar-default .buyforme-content .left").css("height", $(".top-nav .navbar-default .buyforme-content").height() + "px"), $(".top-nav .navbar-default .shipforme-content .left").css("height", $(".top-nav .navbar-default .shipforme-content").height() + "px"), $(".cart-thumb .upload").click(function () {
        $(this).parent().find(".thumb-upload").click()
    }), $(".cart-note .add-note-icon .ck-input").click(function () {
        $(this).parent().css("display", "none"), $(this).parent().parent().find(".form-control").css("display", "block")
    }), $(".cart-text .ck-text .ck-input").click(function () {
        $(this).parent().css("display", "none"), $(this).parent().parent().find(".form-control").css("display", "block")
    }), $(".cart-unit .money .ck-input").click(function () {
        $(this).parent().css("display", "none"), $(this).parent().parent().find(".form-control").css("display", "inline-block")
    }), $(".cart-ship .ck-text .ck-input").click(function () {
        $(this).parent().css("display", "none"), $(this).parent().parent().find(".form-control").css("display", "inline-block")
    }), $(".cart-sale-tax .ck-text .ck-input").click(function () {
        $(this).parent().css("display", "none"), $(this).parent().parent().find(".form-control").css("display", "inline-block")
    }), $(".vat-check input[type='checkbox']").change(function () {
        this.checked ? $(".cart-pay .vat-invoice").slideDown() : $(".cart-pay .vat-invoice").slideUp()
    }), $(".ebay-shop-by").click(function () {
        $(this).hasClass("active") ? $(this).removeClass("active") : $(this).addClass("active")
    }), $(".ebay-shop-by").mouseleave(function () {
        $(this).removeClass("active")
    }), $(".cart-choose-img .nav-tabs > li").click(function () {
        $(".cart-choose-img .nav-tabs > li").hasClass("active") && ($(".cart-choose-img .nav-tabs > li > a > i").removeClass("dot-active").addClass("dot-unactive"), $(this).find("i").removeClass("dot-unactive").addClass("dot-active"))
    }), $("#term-check").click(function () {
        $("#term-check").is(":checked") && $("#term-of-service").modal("show")
    }), $("#itp-brand").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var z = $("#itp-brand").data("owlCarousel");
    $(".itp-product-box .brand .brand-control .arrow-right").click(function (e) {
        e.preventDefault(), z.next()
    }), $(".itp-product-box .brand .brand-control .arrow-left").click(function (e) {
        e.preventDefault(), z.prev()
    }), $("#itp-brand-2").owlCarousel({
        nav: !0,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1e3,
        loop: !0,
        pagination: !1,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1
    });
    var T = $("#itp-brand-2").data("owlCarousel");
    $(".itp-product-box .brand .brand-control .arrow-right-2").click(function (e) {
        e.preventDefault(), T.next()
    }), $(".itp-product-box .brand .brand-control .arrow-left-2").click(function (e) {
        e.preventDefault(), T.prev()
    }), $(".faq-content .content .panel-title a").click(function () {
        $(".faq-content .content .panel-title a .faq-ico").removeClass("minus").addClass("plus"), $(this).hasClass("collapsed") ? $(this).find(".plus").removeClass("plus").addClass("minus") : $(this).find(".minus").removeClass("minus").addClass("plus")
    }), $("#daily-deal").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        items: 1,
        itemsDesktop: !1,
        itemsDesktopSmall: !1,
        itemsTablet: !1,
        itemsMobile: !1,
        autoplay: 1e3
    });
    var M = $("#daily-deal").data("owlCarousel");
    $(".trending .pd-box .content .trending-control .arrow.next").click(function (e) {
        e.preventDefault(), M.next()
    }), $(".trending .pd-box .content .trending-control .arrow.prev").click(function (e) {
        e.preventDefault(), M.prev()
    }), $(".list-size li.man-detail .jump1").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang6").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump2").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang7").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump3").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang8").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump4").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang9").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump5").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang0").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump6").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang1").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump7").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang2").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump8").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang3").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump9").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang4").offset().top - 60}, 600)
    }), $(".list-size li.man-detail .jump0").click(function () {
        $("html, body").animate({scrollTop: $(".product-all .product-size.bang5").offset().top - 60}, 600)
    }), $(".list-double ul li .jump00").click(function () {
        $("html, body").animate({scrollTop: $(".product-all.bang00").offset().top - 60}, 600)
    }), $(".list-size li div.man.jumpman").click(function () {
        $("html, body").animate({scrollTop: $(".product-all.bangman").offset().top - 60}, 600)
    }), $(".list-size li div.woman.jumpwoman").click(function () {
        $("html, body").animate({scrollTop: $(".product-all.bangwoman").offset().top - 60}, 600)
    }), $(".breadcrumb li.popout-hover a").click(function () {
        var e = $(this).parent();
        return $(e).hasClass("active") ? ($(e).removeClass("active"), $(".popout-submenu", $(e)).slideUp()) : ($(e).addClass("active"), $(".popout-submenu", $(e)).slideDown()), !1
    }), $(".product-grid ul").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: !1,
        responsive: {0: {items: 3}, 376: {items: 4}, 640: {items: 4}, 1199: {items: 4}}
    }), $(".thumb-landing a").click(function () {
        var e = $(this).parent(), t = $(this).parent().parent().parent(), i = $(this).attr("href");
        return $(".thumb-landing", $(t)).removeClass("active"), $(e).addClass("active"), $(".main-example img").attr("src", i), !1
    }), $(".dropdown-menu").click(function (e) {
        e.stopPropagation()
    }), $(".alias-box-click").click(function () {
        $(this).toggleClass("active")
    }), $(".DBN-slide-service").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: !0,
        autoplay: !0,
        nav: !0,
        responsive: {
            0: {items: 1},
            376: {items: 1},
            640: {items: 1},
            767: {items: 1},
            991: {items: 5},
            1199: {items: 5}
        }
    }), $("a", $(".menu ul li")).click(function () {
        var e = $(this).attr("href");
        return $("html, body").animate({scrollTop: $(e).offset().top - 80}, 700), !1
    }), $(".menu ul li a").click(function () {
        var e = $(this).parent();
        return $(".menu ul li").removeClass("active"), $(e).hasClass("active") ? $(e).removeClass("active") : $(e).addClass("active"), !1
    }), $("li", $(".pay-left ul")).click(function () {
        $(".pay-left ul li .fa").removeClass("fa-dot-circle-o").addClass("fa-circle-thin"), $(".pay-left ul li.active").removeClass("active"), $(this).addClass("active"), $(".fa", $(this)).removeClass("fa-circle-thin").addClass("fa-dot-circle-o"), $(".pay-right .pr-tab.active").hide().removeClass("active");
        var e = $(this).attr("for");
        return $(e).addClass("active").fadeIn(), !1
    }), $(".lp-cc-other-lines .lp-cc-btn li a").click(function () {
        var e = $(this).parent();
        return $(".lp-cc-other-lines .lp-cc-btn li").removeClass("active"), $(e).hasClass("active") ? $(e).removeClass("active") : $(e).addClass("active"), !1
    }), $(".lsp-cc-other-lines .lsp-cc-btn li a").click(function () {
        var e = $(this).parent();
        return $(".lsp-cc-other-lines .lsp-cc-btn li").removeClass("active"), $(e).hasClass("active") ? $(e).removeClass("active") : $(e).addClass("active"), !1
    }), $(".list-benefit .see-more").click(function () {
        $(".list-benefit .icon-box-text").removeClass("open"), $(".icon-box").removeClass("active"), $(".list-benefit .benefit-content").slideUp(), $(this).parents().eq(4).find(".icon-box").addClass("active"), $(this).parents().eq(2).find(".icon-box-text").addClass("open"), $(this).parents().eq(1).find(".benefit-content").slideDown()
    }), $(".list-benefit .hide").click(function () {
        $(".list-benefit .icon-box-text").removeClass("open"), $(".icon-box").removeClass("active"), $(".list-benefit .benefit-content").slideUp()
    }), $("#lmkt-slider").owlCarousel({
        stagePadding: 150,
        loop: !0,
        margin: 10,
        nav: !1,
        items: 1,
        lazyLoad: !0,
        nav: !0,
        responsive: {
            0: {items: 1, stagePadding: 60},
            600: {items: 1, stagePadding: 100},
            1e3: {items: 1, stagePadding: 200},
            1200: {items: 1, stagePadding: 250},
            1400: {items: 1, stagePadding: 300},
            1600: {items: 1, stagePadding: 350},
            1800: {items: 1, stagePadding: 400}
        }
    }), $(".lmkt-prod-all ul").owlCarousel({
        loop: !0,
        margin: 20,
        nav: !0,
        dots: !1,
        responsive: {
            0: {items: 5},
            1170: {items: 5},
            991: {items: 4},
            767: {items: 3},
            736: {items: 3},
            676: {items: 3},
            640: {items: 2},
            480: {items: 2},
            360: {items: 1}
        }
    }), $(".prod-ads336-all ul").owlCarousel({stagePadding: 70, loop: !0, nav: !1, items: 1, lazyLoad: !0, nav: !0})
});