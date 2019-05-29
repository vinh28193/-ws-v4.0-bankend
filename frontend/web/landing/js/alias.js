var alias = {
    init: function () {
        alias.topus()
        alias.topjp()
    }, topus: function () {
        for ($numberAlias = $("input[name=top-us-slide]").val(), $number = 1; $number <= $numberAlias; $number++) alias.runTopUs("topstore-sub-slider-" + $number)
    }, runTopUs: function (a) {
        $(".navbar-default.navbar-2 .navbar-nav > li > .dropdown-menu div.block-from-us div.TopStore-alias ul li.sub-2").mouseenter(function () {
            $(".navbar-default.navbar-2 .navbar-nav > li > .dropdown-menu div.block-from-us div.TopStore-alias ul li.sub-2").removeClass("open"), $(this).addClass("open");
            var n = $("#" + a).data("owlCarousel");
            n._width = $("#" + a).width(), n.invalidate("width"), n.refresh()
        }), $("#" + a).owlCarousel({
            slideSpeed: 300,
            paginationSpeed: 400,
            loop: !0,
            autoplay: 1e3,
            items: 3,
            margin: 10
        })
    }, topjp: function () {
        for ($numberAlias = $("input[name=top-jp-slide]").val(), $number = 1; $number <= $numberAlias; $number++) alias.runTopJp("topstore-jp-sub-slider-" + $number)
    }, runTopJp: function (a) {
        $(".navbar-default.navbar-2 .navbar-nav > li > .dropdown-menu div.block-from-jp div.TopStore-alias ul li.sub-2").mouseenter(function () {
            console.log(a);
            $(".navbar-default.navbar-2 .navbar-nav > li > .dropdown-menu div.block-from-jp div.TopStore-alias ul li.sub-2").removeClass("open"), $(this).addClass("open");
            var n = $("#" + a).data("owlCarousel");
            n._width = $("#" + a).width(), n.invalidate("width"), n.refresh()
        }), $("#" + a).owlCarousel({
            slideSpeed: 300,
            paginationSpeed: 400,
            loop: !0,
            autoplay: 1e3,
            items: 3,
            margin: 10
        })
    }
};