$(document).ready(function () {
    $('.slick-slider').slick({
        infinite: true,
        vertical: true,
        arrows: true,
        prevArrow: $('.slider-prev'),
        nextArrow: $('.slider-next'),
        slidesToShow: 5
    });
    $('#detail-big-img').ezPlus({
        imageCrossfade: true,
        easing: true,
        scrollZoom: true,
        cursor: 'zoom-in',
        gallery: 'detail-slider',
        galleryActiveClass: 'active'
    });
    $('.style-list').slick({
        infinite: true,
        arrows: true,
        speed: 100,
        loop: false,
        slidesToShow: 6,
        centerMode: false,
        variableWidth: true,
        swipeToSlide: true
    });

    $('.mb-slide-image').slick();
    $('#other-seller').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        dots:true,
        arrows:true,
        responsive:{
            0:{
                items:1
            },
            400:{
                items:2
            },
            600:{
                items:3
            },
            800:{
                items:4
            },
            1000:{
                items:5
            },
            2000:{
                items:6
            }
        }
    });
});
