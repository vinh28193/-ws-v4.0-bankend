$(function () {
    $('img.lazy').lazyload({
            effect: 'fadeIn',
            effectTime: 800,
            threshold: 100,
        }
    );
})
$(window).on('load', function () {
    $('#Selangkah').modal('show');
}), $(document).ready(function () {

    if ($('img').attr('alt') != '' || $('img').attr('alt') != NULL) {
    } else {
        $('img').attr('alt', '');
    }
    ;
    // if ($('#addproducttocart').hasClass('in')) {
    //     $('html').css('overflow-y','hidden');
    //     console.log('Have !! ');
    // } else {
    //     $('html').css('overflow-y','auto');
    // };

    // fix checkout disable

    $(document).ready(function () {
        var checkClass = $("#btnSubmit").hasClass('disabled');
        $('#btnSubmit').each(function () {
            if ($(this).attr('disabled') || $(this).hasClass('disabled') || checkClass == 'true') {
                $(this).off('onclick');
            } else {
                $(this).on('onclick');
            }
        });

    });

    //checkout Bill Request test
    $(document).ready(function () {
        var checkClass = $("#btnCheckoutBill").hasClass('disabled');
        $('#btnCheckoutBill').each(function () {
            if ($(this).attr('disabled') || $(this).hasClass('disabled') || checkClass == 'true') {
                $(this).off('onclick');
            } else {
                $(this).on('onclick');
            }
        });

    });

    $(window).scroll(function () {
        $(window).scrollTop() > 10 ? $('.gotop').addClass('show') : $('.gotop').removeClass('show');
        $(window).scrollTop() > 40 && $(window).width() < 992 ? $('.navbar-ws').addClass('isScroll') : $('.navbar-ws').removeClass('isScroll');
    }), $('.gotop').click(function () {
        $('html, body').animate({scrollTop: 0}, 'slow');
    }), $("[data-toggle='tooltip']").tooltip(), $('.dropdown-menu').click(function (e) {
        e.stopPropagation()
    });
    var e = $("#ws-tag");
    !function a() {
        e.animate({top: "+=5"}, 500), e.animate({top: "-=5"}, 500, a)
    }(), $(document).ready(function () {
        $("#home-slider-2").owlCarousel({
            slideSpeed: 300,
            paginationSpeed: 400,
            loop: !0,
            items: 1,
            itemsDesktop: !1,
            itemsDesktopSmall: !1,
            itemsTablet: !1,
            itemsMobile: !1,
            autoplay: 1e3
        })
    }), $(".menu-toggle").click(function () {
        $(".toggle-navbar").addClass("open"), $(".submenu-bg").show(), $("html").css("overflow-y", "hidden")
    }), $(".toggle-navbar .close").click(function () {
        $(".toggle-navbar").removeClass("open"), $(".submenu-bg").hide(), $("html").css("overflow-y", "inherit")
    }), $(".submenu-bg").click(function () {
        $(".toggle-navbar").removeClass("open"), $(".submenu-bg").hide(), $("html").css("overflow-y", "inherit")
    }), $(".btn-sideleft").click(function () {
        $(".left-menu-beuser").addClass("open"), $(".submenu-bg").show(), $("html").css("overflow-y", "hidden")
    }), $("#sub-cart").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            768: {
                loop: true,
                items: 3
            },
            992: {
                loop: true,
                items: 5
            }
        },
        margin: 10
    });

    $("#ebay-sub-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: 1000,
        items: 5,
        margin: 10
    });
    $("#amazon-sub-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: 1000,
        items: 5,
        margin: 10
    });


    $("#search-result-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: 1000,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            991: {
                items: 3
            }
        }
    });
    $(".brand-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            376: {
                items: 3
            },
            640: {
                items: 4
            },
            991: {
                items: 5
            },
            1199: {
                items: 7
            }
        }
    });
    $(".ldpd-slider1").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            767: {
                items: 3
            },
            991: {
                items: 4
            }
        }
    });


    $("#pd_sugget_session").owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        autoplay: true,
        slideSpeed: 300,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });
    $("#pd_sugget_purchase").owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        autoplay: true,
        slideSpeed: 300,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });
    $("#pd_related").owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        autoplay: true,
        slideSpeed: 300,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });
    $("#pd_suggest").owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        autoplay: true,
        slideSpeed: 300,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });

    $('.navbar-default.navbar-2 .navbar-nav > li:nth-child(1) > .dropdown-menu ul li.sub-2').mouseenter(function () {
        $('.navbar-default.navbar-2 .navbar-nav > li:nth-child(1) > .dropdown-menu ul li.sub-2').removeClass('open');
        $(this).addClass('open');
        var carousel = $('#ebay-sub-slider').data('owlCarousel');
        carousel._width = $('#ebay-sub-slider').width();
        carousel.invalidate('width');
        carousel.refresh();
    });
    $('.navbar-default.navbar-2 .navbar-nav > li:nth-child(2) > .dropdown-menu ul li.sub-2').mouseenter(function () {
        $('.navbar-default.navbar-2 .navbar-nav > li:nth-child(2) > .dropdown-menu ul li.sub-2').removeClass('open');
        $(this).addClass('open');
        var carousel = $('#ebay-sub-slider').data('owlCarousel');
        carousel._width = $('#ebay-sub-slider').width();
        carousel.invalidate('width');
        carousel.refresh();
    });

    $('#addToCartBtn').click(function () {
        var carousel = $('#sub-cart').data('owlCarousel');
        setTimeout(function () {
            carousel._width = $('#sub-cart').width();
            carousel.invalidate('width');
            carousel.refresh();
        }, 200);
    });

    $('.keep-navbar .search-box .search-tag').mouseenter(function () {
        $('.keep-navbar.fixed').addClass('hover');
    });
    $('.keep-navbar .search-box .search-tag').mouseleave(function () {
        $('.keep-navbar.fixed').removeClass('hover');
    });
    $('.keep-navbar .search-box .search-tag').click(function () {
        $('.keep-navbar.fixed').toggleClass('open');
        $('.submenu-bg').toggle();
        // if ($('.keep-navbar.fixed').hasClass('open')) {
        //     $('html').css('overflow-y', 'hidden');
        // } else {
        //     $('html').css('overflow-y', 'auto');
        // }
    });
    $('.navbar-header').click(function () {
        setTimeout(function () {
            var carousel = $('#ebay-sub-slider').data('owlCarousel');
            carousel._width = $('#ebay-sub-slider').width();
            carousel.invalidate('width');
            carousel.refresh();
        }, 100);
    });
    $('.navbar-header').click(function () {
        setTimeout(function () {
            var carousel = $('#amazon-sub-slider').data('owlCarousel');
            carousel._width = $('#amazon-sub-slider').width();
            carousel.invalidate('width');
            carousel.refresh();
        }, 100);
    });
    // $('.navbar-2 ul li.dropdown').on('show.bs.dropdown', function (e) {
    //     $('.submenu-bg').show();
    //     // $('html').css('overflow-y','hidden');
    // });
    // $('.navbar-2 ul li.dropdown').on('hide.bs.dropdown', function (e) {
    //     $('.submenu-bg').hide();
    //     // $('html').css('overflow-y','auto');
    // });
    $('.keep-navbar .cate-nav ul li.globe-sub').mouseenter(function () {
        if ($('.keep-navbar').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').addClass('open');
        }
    });
    $('.keep-navbar .cate-nav ul li.globe-sub').mouseleave(function () {
        if ($('.keep-navbar').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').removeClass('open');
        }
    });

    $('.keep-navbar.amazon .cate-nav ul li.globe-sub').mouseenter(function () {
        if ($('.keep-navbar.amazon').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').addClass('open');
        }
    });
    $('.keep-navbar.amazon .cate-nav ul li.globe-sub').mouseleave(function () {
        if ($('.keep-navbar.amazon').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').removeClass('open');
        }
    });

    $('.keep-navbar.ebay .cate-nav ul li.globe-sub').mouseenter(function () {
        if ($('.keep-navbar.ebay').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').addClass('open');
        }
    });
    $('.keep-navbar.ebay .cate-nav ul li.globe-sub').mouseleave(function () {
        if ($('.keep-navbar.ebat').hasClass('fixed')) {
            $('.keep-navbar .cate-nav ul li.globe-sub').removeClass('open');
        }
    });

    // $('.amz-browser-content .left .cate-item .top-cate li .sub-cate .fa').click(function () {
    //     $('.amz-browser-content .left .cate-item .top-cate li').removeClass('open');
    //     $('.amz-browser-content .left .cate-item .top-cate li .sub-cate .fa').removeClass('fa-angle-down').addClass('fa-angle-right');
    //     $(this).parent().parent().addClass('open');
    //     $(this).removeClass('fa-angle-right').addClass('fa-angle-down');
    // });

    $('.keep-navbar .search-box .form-group .input-group-btn:first-child > .btn').on('show.bs.dropdown', function (e) {
        $('.submenu-bg').show();
        $('html').css('overflow-y', 'hidden');
    });
    $('.keep-navbar .search-box .form-group .input-group-btn:first-child > .btn').on('hide.bs.dropdown', function (e) {
        $('.submenu-bg').hide();
        $('html').css('overflow-y', 'auto');
    });

    $('.product-info-box .product-info .content .payment-box .pm-1 .detail').click(function () {
        $('.product-info-box .product-info .content .payment-box .pm-2').slideToggle();
        $(this).toggleClass('open');
    });
    $('.modal').on('hidden.bs.modal', function () {
        // $('html').css('overflow-y','auto');
    });

    $(".product-new-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            376: {
                items: 3
            },
            640: {
                items: 4
            },
        }
    });
    $(".mb-pd-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            414: {
                items: 2
            }
        }
    });

    $(".small-banner-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        items: 2
    });

    $(".pt-brand-slider").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            376: {
                items: 3
            },
            640: {
                items: 5
            }
        }
    });
    $('.product-info-box .slider-info .right .favorite-box .favorite-btn').click(function () {
        $('.product-info-box .slider-info .right .favorite-box .favorite-list').slideToggle(300);
    });

    //Cart note
    $('.cart-note .add-note-icon .ck-input').click(function () {
        $(this).parent().css('display', 'none');
        $(this).parent().parent().find('.form-control').css('display', 'block');
    });
    $('.cart-text .ck-text .ck-input').click(function () {
        $(this).parent().css('display', 'none');
        $(this).parent().parent().find('.form-control').css('display', 'block');
    });
    $('.cart-unit .money .ck-input').click(function () {
        $(this).parent().css('display', 'none');
        $(this).parent().parent().find('.form-control').css('display', 'inline-block');
    });
    $('.cart-ship .ck-text .ck-input').click(function () {
        $(this).parent().css('display', 'none');
        $(this).parent().parent().find('.form-control').css('display', 'inline-block');
    });
    $('.cart-sale-tax .ck-text .ck-input').click(function () {
        $(this).parent().css('display', 'none');
        $(this).parent().parent().find('.form-control').css('display', 'inline-block');
    });

    //VAT form check
    $(".vat-check input[type='checkbox']").change(function () {
        if (this.checked) {
            $('.cart-pay .vat-invoice').slideDown();
        } else {
            $('.cart-pay .vat-invoice').slideUp();
        }
    });

    $('.navbar-default.navbar-2 ul li.dropdown .dropdown-toggle').removeAttr('data-toggle');
    $('.navbar-default.navbar-2 ul li.dropdown').mouseenter(function () {
        $(this).addClass('open');
        $('.submenu-bg').show();
        $(this).parent().click();
    });
    $('.navbar-default.navbar-2 ul li.dropdown').mouseleave(function () {
        $(this).removeClass('open');
        $('.submenu-bg').hide();
    });
    $('.navbar-default.navbar-2 ul li.active').mouseenter(function () {
        $('.submenu-bg').hide();
    });

    //Checkout Tabs
    $('.payment-option .nav-tabs > li').click(function () {
        if ($('.payment-option .nav-tabs > li').hasClass('active')) {
            $('.payment-option .nav-tabs > li > a > i').removeClass('dot-active').addClass('dot-unactive');
            $(this).find('i').removeClass('dot-unactive').addClass('dot-active');
        }
    });

    $('.left-menu .menu-list ul li.portal.amazon-sub .sub-btn').click(function () {
        $('.left-menu .menu-list .menu-content').addClass('open');
        $(this).parent().addClass('active');
        $('.left-menu').addClass('amz-leftmn');
    });
    $('.left-menu .menu-list ul li.portal.ebay-sub .sub-btn').click(function () {
        $('.left-menu .menu-list .menu-content').addClass('open');
        $(this).parent().addClass('active');
        $('.left-menu').addClass('ebay-leftmn');
    });

    $('.left-menu .menu-list ul li .sub-content ul li.back').click(function () {
        $('.left-menu .menu-list .menu-content').removeClass('open');
        $('.left-menu .menu-list ul li').removeClass('active');
        $('.left-menu').removeClass('amz-leftmn').removeClass('ebay-leftmn');
    });

    // landing-top-store
    $(".banner-slide ul").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        nav: true,
        dots: true,
        responsive: {
            0: {
                items: 1
            },
        }
    });

    $(".brand-slider2").owlCarousel({
        slideSpeed: 300,
        paginationSpeed: 400,
        loop: true,
        autoplay: true,
        margin: 15,
        responsive: {
            0: {
                items: 2
            },
            376: {
                items: 3
            },
            640: {
                items: 4
            },
            991: {
                items: 5
            },
            1199: {
                items: 6
            }
        }
    });
    $('.ship-info .switch-btn .switch').click(function () {
        $(this).toggleClass('on');
    });
    $("#content-slide").owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: false,
        nav: false,
        dots: true,
        autoplay: false,
        autoHeight: false,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        navText: false,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    });
    $("#feed-detail").owlCarousel({
        animateOut: 'fadeOut',
        nav: true,
        slideSpeed: 300,
        paginationSpeed: 400,
        autoplay: 1000,
        loop: true,
        pagination: false,
        items: 1,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false
    });

    // new js
    $('.amz-browser-content .right .amz-pd-list .bottom .pagination li').on('click', function () {
        $('html, body').animate({scrollTop: 0}, 'slow');
    })
    $('input[name="searchTop"]').on('keyup', function () {
        $('.search-placeholder').hide();
    });
});