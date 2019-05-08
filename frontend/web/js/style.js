// Created by nktquan@gmail.com

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
        items: 6,
        dots: false
    });

    $("#product-viewed-2").owlCarousel({
        slideSpeed : 300,
        paginationSpeed : 400,
        loop: true,
        nav: true,
        autoplay: 1000,
        items: 5,
        dots: false
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
});