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
        prevArrow: $('.slider-prev2'),
        nextArrow: $('.slider-next2'),
        slidesToShow: 6
    });
});
