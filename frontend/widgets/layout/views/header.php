<?php
$js = <<<JS
$.ajax({
                url: '/checkout/cart/count',
                method: 'GET',
                success: function (res) {
                console.log(res);
                    if (res.success) {
                        $('#count-cart').html(res.count);
                    } else {
                        $('#count-cart').html('0');
                    }
                }
            });
$(document).ready(function () {
    $('.bars-cate-mb').slick({
      infinite: true,
      arrows: false,
      speed: 100,
      loop: false,
      slidesToShow: 3,
      centerMode: false,
      variableWidth: true,
      swipeToSlide: true
    });
    });
    $('.toggle').click(function() {
    var check = $('#target').css('display');
    if(check == 'none')
        $('#asd').css('transform','scaleY(-1)');
    else
        $('#asd').css('transform','scaleY(1)');
    $('#target').slideToggle('fast');
});

    $('.toggleEbay').click(function() {
        var check1 = $('#targetEbay').css('display');
    if(check1 == 'none')
        $('#asd1').css('transform','scaleY(-1)');
    else
        $('#asd1').css('transform','scaleY(1)');
    $('#targetEbay').slideToggle('fast');
});
    
    $(document).ready(function() {
 
    var div = $('#header');
    var start = $(div).offset().top;
 
    $.event.add(window, "scroll", function() {
        var p = $(window).scrollTop();
        $(div).css('position',((p)>start) ? 'fixed' : 'static');
        $(div).css('width',((p)>start) ? '100%' : '100%');
        $(div).css('padding-left',((p)>start) ? '5%' : '5%');
        $(div).css('top',((p)>start) ? '0px' : '');
    });
 
});
JS;
$this->registerJs($js);

?>
<div class="navbar-ws mobile-hide style-header" id="header" xmlns="http://www.w3.org/1999/html">
    <div class="container row">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title="">
            </a>
        </div>
        <div class="search-box">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="searchBoxInput" id="searchBoxInput" class="form-control" value="<?= Yii::$app->request->get('keyword','') ?>"
                           placeholder="Nhập tên sản phẩm hoặc đường link sản phẩm amzon.com, ebay.com tại đây">
                    <span class="input-group-btn">
                <button type="button" id="searchBoxButton" class="btn btn-default">
                    <i id="ico-search" class="la la-search"></i>
                </button>
            </span>
                </div>
            </div>
        </div>
        <div class="cart-header-box">
            <i class="la la-shopping-cart"></i>
            <a href="/my-cart.html"><span class="label-cart">Giỏ hàng (<span id="count-cart">0</span>)</span></a>
        </div>
        <div class="account-header-box dropdown style-account" style="width: 150px">
            <a class="bg-white" id="dropAcount" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
               aria-expanded="false">
                <span class="row">
                    <span class="col-md-3 m-0 pr-0 float-right">
                        <i class="la la-user"></i>
                    </span>
                    <span class="col-md-9 m-0  pl-0">
                        <span href="javascript: void(0);" class="option-auth"><?= Yii::$app->user->isGuest ? Yii::t('frontend','Đăng ký / đăng nhập') : Yii::$app->user->getIdentity()->first_name.' '.Yii::$app->user->getIdentity()->last_name ?></span><br>
                        <span class="account-title">Tài khoản<i class="la la-caret-down"></i></span>
                    </span>
                </span>
            </a>
            <ul id="menuAccount" class="dropdown-menu category_account" role="menu" aria-labelledby="dropAcount"
                style="display: none">
                <?php if(Yii::$app->user->isGuest) {?>
                <li class="card" style="border: none">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Bạn đăng nhập nhanh bằng tài khoản của</span>
                            </div>
                            <div class="col-md-6 pr-1">
                                <button class="btn btn-fb">
                                    <i class="la la-facebook style-facebook"></i>
                                    <span class="style-font-facebook">Facebook</span>
                                </button>
                            </div>
                            <div class="col-md-6 pl-1">
                                <button  class="btn btn-google">
                                    <i class="la la-google style-facebook"></i>
                                    <span class="style-font-facebook">Google</span>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Hoặc đăng nhập băng email</span>
                            </div>
                            <div class="col-md-12">
                                <div class="social-button">
                                    <a href="/login.html" class="btn btn-info">Đăng nhập tài khoản email</a>
                                </div>
                            </div>
                        </div>
                        <hr class="color-account">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Bạn chưa có tài khoản ?</span>
                            </div>
                            <div class="col-md-12 social-button">
                                <a href="/signup.html" class="btn btn-amazon">Bạn đăng kí ngay tại đây</a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php }else{?>
                <li class="card" style="border: none">
                    <div class="card-body">
                       <div class="row">
                           <div class="col-md-12 mb-2">Đơn hàng của tôi</div>
                           <div class="col-md-12 mb-2">Mã giảm giá của tôi</div>
                           <div class="col-md-12 mb-2">Khiếu nại & hoàn trả</div>
                           <div class="col-md-12 mb-2">Tài khoản ví(xu)</div>
                           <div class="col-md-12 mb-3">
                               <a href="/my-weshop.html">Cấu hình tài khoản</a>
                           </div>
                           <div class="col-md-12 mb-2 social-button">
                               <a class="btn btn-info p-2" href="/logout.html">Đăng xuất tài khoản</a>
                           </div>
                       </div>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container menu-cate">
        <ul class="bars-nav bars-nav123">
            <li class="dropdown active mr-1">
                <a id="drop1" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
                   aria-expanded="false" style="display: inline-flex;">
                    <span style="padding-top: 3px;display: block;" class=""><i class="la la-bars"></i></span>
                    <span style="display: block;">Danh mục sản phẩm</span>
                    <span style="display: block;"><i class="la la-caret-down"></i></span>
                </a>
                <ul id="menu1" class="dropdown-menu category_list style-u" role="menu" aria-labelledby="drop1"
                    style="display: none; border-right: 1px solid gray ">
                    <li role="presentation">
                        <a data-toggle="collapse" class="toggle" id="toggle" role="button" aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_amazon_us.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="ico-dropdown amz"><i id="asd"
                                        class="la la-caret-down" style="transform: scaleY(-1);"></i></span>
                        </a>
                        <ul class="style-ull menu" id="target" style="display: block;">
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A']) ?>
                        </ul>
                    </li>
                    <hr class="m-0">
                    <li role="presentation">
                        <a href="javascript: void(0);" class="toggleEbay" data-toggle="dropdown" aria-haspopup="true" role="button"
                           aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_ebay.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="ico-dropdown ebay"><i id="asd1"
                                        class="la la-caret-down"></i></span>
                        </a>
                        <ul class="style-ull" id="targetEbay" >
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E']) ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);">Daily deal</a>
            </li>
            <li>
                <a href="javascript:void(0);">Thương hiệu nổi tiếng</a>
            </li>
            <li>
                <a href="javascript:void(0);">Đồng hồ</a>
            </li>
            <li>
                <a href="javascript:void(0);">Nước hoa</a>
            </li>
            <li>
                <a href="javascript:void(0);">Nước hoa</a>
            </li>
            <li>
                <a href="javascript:void(0);" style="padding-left: 0px">
                    <i class="la la-hand-o-right"></i>
                    Dùng thử dịch vụ Prime
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="mb-wrapper mobile-show">
    <nav class="mb-navbar">
        <span class="btn-toggle la la-bars"></span>
        <a href="/" class="logo"><img src="/images/logo/weshop-01.png" alt=""></a>
        <ul class="action">
            <li><a href="javascript:void(0);" class="auth-user">
                    <i class="la la-user"></i>
                    Tài khoản
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="la la-shopping-cart"></i>
                    <span class="badge">2</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="mb-menu">
        <div class="title-mb-menu">
            <i class="la la-close"></i>
            <span>Danh mục sản phẩm</span>
        </div>
        <div class="content-cate-mb">
            <ul class="mb-menu-cate">
                <li role="presentation">
                    <div class="title-submenu">
                        <a class="dropdown-collapse" data-toggle="collapse"
                           data-target="#category-mb-amazon" style="display: block"
                           aria-expanded="true" aria-controls="collapseOne">
                            <img src="/img/logo_amazon_us.png" style="height: 22px">
                            <i class="la la-angle-down alert-right"></i>
                        </a>
                    </div>
                    <div class="clearfix submenu-2 collapse show" id="category-mb-amazon">
                        <ul>
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A','logoMobile' => '/img/logo_amazon_us.png' ,'type_monitor'=>'mobile']) ?>
                        </ul>
                    </div>
                </li>
                <li role="presentation">
                    <div class="title-submenu">
                        <a class="dropdown-collapse" data-toggle="collapse"
                           data-target="#category-mb-ebay" style="display: block"
                           aria-expanded="true" aria-controls="collapseOne">
                            <img src="/img/logo_ebay.png" style="height: 22px">
                            <i class="la la-angle-right alert-right"></i>
                        </a>
                    </div>
                    <div class="clearfix submenu-2 collapse" id="category-mb-ebay">
                        <ul>
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E','logoMobile' => '/img/logo_ebay.png' ,'type_monitor'=>'mobile']) ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="mb-modal-auth">
        <div class="title-mb-menu">
            <a href="/" class="logo"><img src="/images/logo/weshop-01.png" alt="" style="height: 30px"></a>
            <i class="la la-close" style="float: right;padding:5px;"></i>
            <div class="clearfix"></div>
        </div>
        <div class="content-modal-auth-mb">
            <div class="row">
                <div class="col-12"><span><?= Yii::t('frontend', 'Login with') ?></span></div>
                <div class="col-6">
                    <button class="btn btn-fb">Facebook</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-google">Google</button>
                </div>
                <div class="col-12"><span><?= Yii::t('frontend', 'Or login with email') ?></span></div>
                <div class="col-12">
                    <button class="btn btn-info"><?= Yii::t('frontend', 'Login with email') ?></button>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12"><span><?= Yii::t('frontend', 'Are you have not account?') ?></span></div>
                <div class="col-12">
                    <button class="btn btn-amazon"><?= Yii::t('frontend', 'Register here') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="search-box-mobile">
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="searchBoxInput" id="searchBoxInput" class="form-control" value="<?= Yii::$app->request->get('keyword','') ?>"
                       placeholder="Nhập từ khoá hoặc link sản phẩm">
                <span class="input-group-btn">
                <button type="button" id="searchBoxButton" class="btn btn-default">
                    <i id="ico-search" class="la la-search"></i>
                </button>
            </span>
            </div>
        </div>
    </div>
    <div class="container menu-cate-mb">
        <ul class="bars-cate-mb">
            <li>
                <a href="javascript:void(0);">Daily deal</a>
            </li>
            <li>
                <a href="javascript:void(0);">Thương hiệu nổi tiếng</a>
            </li>
            <li>
                <a href="javascript:void(0);">Đồng hồ</a>
            </li>
            <li>
                <a href="javascript:void(0);">Nước hoa</a>
            </li>
            <li>
                <a href="javascript:void(0);">Nước hoa</a>
            </li>
            <li>
                <i class="la la-hand-o-right"></i>
                <a href="javascript:void(0);">Dùng thử dịch vụ Prime</a>
            </li>
        </ul>
    </div>
</div>