$( document ).ready(function() {

    // Slider

    $('#home-slider').owlCarousel({
        loop:true,
        nav:true,
        items: 1
    });

    $("#globe-sub-slider1").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        autoplay: 1000,
        items: 4,
        nav:true,
        dots: false
    });

    $("#globe-sub-slider2").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        autoplay: 1000,
        items: 4,
        nav:true,
        dots: false
    });

    $("#globe-sub-slider3").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        autoplay: 1000,
        items: 4,
        nav:true,
        dots: false
    });

    $("#globe-sub-slider4").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        autoplay: 1000,
        items: 4,
        nav:true,
        dots: false
    });

    $("#product-viewed").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        nav: true,
        autoplay: 1000,
        responsive : {
            0: {
                items: 3,
            },
            575: {
                items: 4,
            },
            768: {
                items: 5,
            }
        },
        dots: false
    });

    $("#product-viewed-2").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        nav: true,
        autoplay: 1000,
        responsive : {
            0: {
                items: 3,
            },
            575: {
                items: 4,
            },
            768: {
                items: 5,
            }
        },
        dots: false
    });

    $("#brand-slider").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        nav: true,
        autoplay: 1000,
        responsive : {
            0: {
                items: 3,
            },
            575: {
                items: 4,
            },
            768: {
                items: 5,
            }
        },
        dots: false
    });

    $("#banner-item-slider").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        autoplay: 1000,
        items: 1,
        dots: true
    });

    $('.dropdown-menu').click(function(e) {
        e.stopPropagation();
    });

    $('.other-page .globe-sub').mouseenter(function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
        } else {
            $(this).addClass('open');
        }
    });

    $('.other-page .globe-sub').mouseleave(function () {
        $(this).removeClass('open');
    });

    $('.detail-block .see-more').click(function() {
        $(this).toggleClass('open');
        $('.detail-block .info-list').toggleClass('open');
    });

    if($(window).width() < 768) {
        $('.navbar-2').click(function () {
            if ($(this).hasClass('open')) {
                return;
            } else {
                $(this).addClass('open');
            }
        });

        $(document).mouseup(function(e) {
            var navbar = $(".navbar-2");
            if (!navbar.is(e.target) && navbar.has(e.target).length === 0) {
                navbar.removeClass('open');
            }
        });
    };

    $('.navbar-2 .navbar-header ul li .dropdown-menu ul>li.sub-2>a').mouseenter(function () {
        $('.navbar-2 .navbar-header ul li .dropdown-menu ul li.sub-2').removeClass('open');
        $(this).parent().addClass('open');
    });
    $('.dropdown-menu .title2').click(function () {
        var id = $(this).attr('data-toggle');
        $('.dropdown-menu .title2').removeClass('active');
        $('.dropdown-menu .content-tab').css('display','none');
        $(this).addClass('active');
        $(this).addClass(id);
        $('#'+id).css('display','block');
    });
    if($(window).width() < 768) {
        $('.navbar-2').click(function () {
            if ($(this).hasClass('be-header')) {
                return;
            } else {
                if ($(this).hasClass('open')) {
                    return;
                } else {
                    $(this).addClass('open');
                }
            }
        });

        $(document).mouseup(function(e) {
            var navbar = $(".navbar-2");
            if (!navbar.is(e.target) && navbar.has(e.target).length === 0) {
                navbar.removeClass('open');
            }
        });

        $('.be-toggle').click(function () {
            $('.be-menu').addClass('open');
        });

        $(document).mouseup(function(e) {
            var be_menu = $(".be-menu");
            if (!be_menu.is(e.target) && be_menu.has(e.target).length === 0) {
                be_menu.removeClass('open');
            }
        });


        $('.mb-navbar .btn-toggle').click(function () {
            $('.mb-menu').toggleClass('open');
        });
        $('.title-mb-menu i').click(function () {
            $('.mb-menu').removeClass('open');
        });

        $('#showFilter-mb').click(function () {
            $('.mb-menu-filter').toggleClass('open');
        });
        $('.mb-menu-filter .title-mb-menu i').click(function () {
            $('.mb-menu-filter').removeClass('open');
        });

        $('.mb-navbar .auth-user').click(function () {
            $('.mb-modal-auth').toggleClass('open');
        });
        $('.title-mb-menu i').click(function () {
            $('.mb-modal-auth').removeClass('open');
        });

        $('.mb-menu-cate .title-submenu').click(function () {
            var id_to = $(this).attr('id');
            if($('div[data-toggle='+id_to+']').css('display') === 'none'){
                $('div[data-toggle='+id_to+']').css('display','block');
                $('#'+id_to+' .la-caret-down').addClass('la-caret-up');
                $('#'+id_to+' .la-caret-down').removeClass('la-caret-down');
            }else{
                $('div[data-toggle='+id_to+']').css('display','none');
                $('#'+id_to+' .la-caret-up').addClass('la-caret-down');
                $('#'+id_to+' .la-caret-up').removeClass('la-caret-up');
            }
        });

        $('.title-submenu .dropdown-collapse').click(function () {
            if($(this.getElementsByTagName('i')).hasClass('la-angle-right')){
                $(this.getElementsByTagName('i')).removeClass('la-angle-right');
                $(this.getElementsByTagName('i')).addClass('la-angle-down');
            }else{
                $(this.getElementsByTagName('i')).removeClass('la-angle-down');
                $(this.getElementsByTagName('i')).addClass('la-angle-right');
            }
        });

        $(document).mouseup(function(e) {
            var mb_menu = $(".mb-menu");
            if (!mb_menu.is(e.target) && mb_menu.has(e.target).length === 0) {
                mb_menu.removeClass('open');
            }
            var mb_menu_filter = $(".mb-menu-filter");
            if (!mb_menu_filter.is(e.target) && mb_menu_filter.has(e.target).length === 0) {
                mb_menu_filter.removeClass('open');
            }
        });
    };
});
