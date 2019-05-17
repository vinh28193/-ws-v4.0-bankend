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

    $('.navbar-2 .navbar-header ul li .dropdown-menu ul li').mouseenter(function () {
        $('.navbar-2 .navbar-header ul li .dropdown-menu ul li').removeClass('open');
        $(this).addClass('open');
    });
    $('.checkout-step li').click(function () {
        var step = $(this)[0].firstElementChild.innerHTML;
        if($('#step_checkout_'+step).length === 1){
            $('#step_checkout_1').css('display','none');
            $('#step_checkout_2').css('display','none');
            $('#step_checkout_3').css('display','none');
            $('#step_checkout_'+step).css('display','block');
        }
    });
    $('input[name=check-member]').click(function () {
        var value = $(this).val();
        if(value === 'new-member'){
            $('div[data-merge=signup-form]').css('display','block');
        }else {
            $('div[data-merge=signup-form]').css('display','none');
        }
    });
    $('input').change(function () {
       var name = $(this).attr('name');
       $('#'+name+'-error').html('');
    });
    $('#loginToCheckout').click(function () {
        ws.loading(true);
        var typeLogin = $('input[name=check-member]:checked').val();
        var loginForm = {};
        var SignupForm = {};
        var url = 'checkout.html';
        if(typeLogin === 'new-member'){
            SignupForm = {
                email: $('input[name=email]').val(),
                password: $('input[name=password]').val(),
                replacePassword: $('input[name=replacePassword]').val(),
                first_name: $('input[name=first_name]').val(),
                last_name: $('input[name=last_name]').val(),
                phone: $('input[name=phone]').val(),
            };
            url = 'checkout/signup.html';
        }else {
            loginForm = {
                email: $('input[name=email]').val(),
                password: $('input[name=password]').val(),
                rememberMe: $('input[name=rememberMe]').val(),
            };
            url = 'checkout/login.html';
        }
        $.ajax({
            url: url,
            method: "POST",
            data: {
                LoginForm: loginForm,
                SignupForm: SignupForm,
                rel: location.href,
            },
            success: function (result) {
                if (result.success) {
                    window.location.reload();
                } else {
                    ws.loading(false);
                    $('label[data-href]').html('');
                    $.each(result.data, function (k,v) {
                        $('#'+k+'-error').html(v[0]);
                    })
                }
            }
        });
    });
});