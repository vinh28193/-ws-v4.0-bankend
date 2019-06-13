<?php
$js = <<<JS
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
    $('#target').toggle('slow');
});

    $('.toggleEbay').click(function() {
        var check1 = $('#targetEbay').css('display');
    if(check1 == 'none')
        $('#asd1').css('transform','scaleY(-1)');
    else
        $('#asd1').css('transform','scaleY(1)');
    $('#targetEbay').toggle('slow');
});
JS;
$this->registerJs($js);

use yii\helpers\Html; ?>
<div class="navbar-ws mobile-hide" xmlns="http://www.w3.org/1999/html">
    <div class="container row">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title="" width ="175px"/ >
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
            <span class="label-cart">Giỏ hàng (10)</span>
        </div>
        <div class="account-header-box dropdown style-account" style="width: 150px">
            <a class="bg-white" id="dropAcount" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
               aria-expanded="false">
                <span class="row">
                    <span class="col-md-3 m-0 pr-0 float-right">
                        <i class="la la-user"></i>
                    </span>
                    <span class="col-md-9 m-0  pl-0 text-center">
                        <span href="javascript: void(0);" class="option-auth">Đăng ký / đăng nhập</span><br>
                        <span class="account-title">Tài khoản</span>
                    </span>
                </span>
            </a>
<!--            <ul id="menuAccount" class="dropdown-menu category_list category_account" role="menu" aria-labelledby="dropAcount"-->
<!--                style="display: none; border-right: 1px solid gray">-->
<!--                <li class="card" style="border: none">-->
<!--                    <div class="card-body">-->
<!--                       <div class="row">-->
<!--                           <div class="col-md-12 mb-2">Đơn hàng của tôi</div>-->
<!--                           <div class="col-md-12 mb-2">Mã giảm giá của tôi</div>-->
<!--                           <div class="col-md-12 mb-2">Khiếu nại & hoàn trả</div>-->
<!--                           <div class="col-md-12 mb-2">Tài khoản ví(xu)</div>-->
<!--                           <div class="col-md-12 mb-3">Cấu hình tài khoản</div>-->
<!--                           <div class="col-md-12 mb-2">-->
<!--                               <button class="btn style-font pt-2 pb-2" style="background-color: #2b96b6; width: 100%">Đăng xuất tài khoản</button>-->
<!--                           </div>-->
<!--                       </div>-->
<!--                    </div>-->
<!--                </li>-->
<!--            </ul>-->
            <ul id="menuAccount" class="dropdown-menu category_list category_account" role="menu" aria-labelledby="dropAcount"
                style="display: none; border-right: 1px solid gray">
                <li class="card" style="border: none">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Bạn đăng nhập nhanh bằng tài khoản của</span>
                            </div>
                            <div class="col-md-6 pr-1">
                                <button class="btn btn-fb" style="background-color: #415a98; width: 100%">
                                    <i class="la la-facebook style-font"></i>
                                    <span class="style-font">Facebook</span>
                                </button>
                            </div>
                            <div class="col-md-6 pl-1">
                                <button  class="btn btn-google" style="background-color: #3d82f1; width: 100%">
                                    <i class="la la-google style-font"></i>
                                    <span class="style-font">Google</span>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Hoặc đăng nhập băng email</span>
                            </div>
                            <div class="col-md-12">
                                <div class="social-button">
                                    <button class="btn style-font" style="background-color: #2b96b6; width: 100%">Đăng nhập tài khoản email</button>
                                </div>
                            </div>
                        </div>
                        <hr class="color-account">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="color-account">Bạn chưa có tài khoản ?</span>
                            </div>
                            <div class="col-md-12">
                                <button class="btn style-font" style="width: 100%; background-color: #e67424;">Bạn đăng kí ngay tại đây</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="container menu-cate">
        <ul class="bars-nav bars-nav123">
            <li class="dropdown active mr-1">
                <a id="drop1" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
                   aria-expanded="false">
                    <i class="la la-bars"></i>
                    Danh mục sản phẩm
                    <i class="la la-caret-down"></i>
                </a>
                <ul id="menu1" class="dropdown-menu category_list style-u" role="menu" aria-labelledby="drop1"
                    style="display: none; border-right: 1px solid gray ">
                    <li role="presentation">
                        <a data-toggle="collapse" class="toggle" href="#collapseExample" id="toggle" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <span style="display: block; float: left;"><img src="/img/logo_amazon_us.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="amz"><i id="asd"
                                        class="la la-caret-down"></i></span>
                        </a>
                        <ul class="style-ull menu" id="target">
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A']) ?>
                        </ul>
                    </li>
                    <hr class="m-0">
                    <li role="presentation">
                        <a href="javascript: void(0);" class="toggleEbay" data-toggle="dropdown" aria-haspopup="true" role="button"
                           aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_ebay.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="ebay"><i id="asd1"
                                        class="la la-caret-down"></i></span>
                        </a>
                        <ul class="style-ull" id="targetEbay" >
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E']) ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">Daily deal</a>
            </li>
            <li>
                <a href="#">Thương hiệu nổi tiếng</a>
            </li>
            <li>
                <a href="#">Đồng hồ</a>
            </li>
            <li>
                <a href="#">Nước hoa</a>
            </li>
            <li>
                <a href="#">Nước hoa</a>
            </li>
            <li>
                <i class="la la-hand-o-right"></i>
                <a href="#">Dùng thử dịch vụ Prime</a>
            </li>
        </ul>
    </div>
</div>
<div class="mb-wrapper mobile-show">
    <nav class="mb-navbar">
        <span class="btn-toggle la la-bars"></span>
        <a href="#" class="logo"><img src="/images/logo/weshop-01.png" alt=""></a>
        <ul class="action">
            <li><a href="#" class="auth-user">
                    <i class="la la-user"></i>
                    Tài khoản
                </a>
            </li>
            <li>
                <a href="#">
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
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap1.png" alt="">
                                    </i>Women's Fashion </a>
                                <i class="la la-angle-right float-right mt-2" onclick="showTab('sub-tab-cate-0')"></i>
                                <div class="sub-cate-2" id="sub-tab-cate-0">
                                    <div class="title-submenu">
                                        <a style="display: block"
                                           aria-expanded="true" >
                                            <img src="/img/logo_amazon_us.png" style="height: 22px">
                                        </a>
                                    </div>
                                    <div class="content-menu-cate-sub-2">
                                        <div class="title-submenu">
                                            <i class="la la-angle-left float-left" onclick="hideTab('sub-tab-cate-0')"></i>
                                            <a href="javascript:void(0)">
                                                Women's Fashion
                                            </a>
                                        </div>
                                        <div class="clearfix submenu-2 collapse show" id="category-mb-amazon">
                                            <ul>
                                                <?php /* foreach ($categories as $index => $category): ?>
                                                    <li class="accordion">
                                                        <?= Html::a($category['category_name'], $url_cate($category['category_id']), ['onclick' => "ws.loading(true);"]); ?>
                                                        <?php if (isset($category['child_category']) && ($childs = $category['child_category']) !== null && count($childs) > 0): ?>
                                                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-<?= $index; ?>"
                                                               aria-expanded="true" aria-controls="collapseOne"><i class="la la-angle-right alert-right"></i></a>
                                                            <div id="sub-<?= $index; ?>" class="collapse" aria-labelledby="headingOne"
                                                                 data-parent="#sub-menu-collapse">
                                                                <ul class="sub-category">
                                                                    <?php foreach ($childs as $child): ?>
                                                                        <li>
                                                                            <?= Html::a($child['category_name'], $url_cate($child['category_id']), []); ?>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            </div>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; */?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap2.png" alt="">
                                    </i>Home & Garden
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap3.png" alt="">
                                    </i>Home Furniture
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap4.png" alt="">
                                    </i>Shoes, Bags & Accessories
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap5.png" alt="">
                                    </i>Toys, Mother & Kid
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap6.png" alt=""></i>Moblie and
                                    Electronics
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                                <div class="sub-menu-2">
                                    <div class="ebay-sub-menu ebay ml-1">
                                        <div class="row">
                                            <div class="col-md-6 pl-4">
                                                <div class="title-box">
                                                    <div class="title">Shop Amazon</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To
                                                        door Delivery
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Amazon Video</div>
                                                            <ul>
                                                                <li><a href="#">All videos</a></li>
                                                                <li><a href="#">Included with Prime</a></li>
                                                                <li><a href="#">Add-on Subscriptions</a></li>
                                                                <li><a href="#">Rent or Buy</a></li>
                                                                <li><a href="#">Free to Watch</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">More to Explore</div>
                                                            <ul>
                                                                <li><a href="#">Video shots</a></li>
                                                                <li><a href="#">Style Code Live</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">More to Explore</div>
                                                            <ul>
                                                                <li><a href="#">Video shots</a></li>
                                                                <li><a href="#">Style Code Live</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Amazon Video</div>
                                                            <ul>
                                                                <li><a href="#">All videos</a></li>
                                                                <li><a href="#">Included with Prime</a></li>
                                                                <li><a href="#">Add-on Subscriptions</a></li>
                                                                <li><a href="#">Rent or Buy</a></li>
                                                                <li><a href="#">Free to Watch</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="banner-sub">
                                                    <a href="#">
                                                        <img src="https://static-v3.weshop.com.vn/uploadImages/bfe161/bfe1618603f223133d2b50108bef400a.jpg"
                                                             alt="" title=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap7.png" alt="">
                                    </i>Beauty & Health
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap8.png" alt=""></i>Men's Fashion
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap9.png" alt="">
                                    </i>Watches & Accessories
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap10.png" alt="">
                                    </i>Sports & Outdoors
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i"><img class="style-img" src="/images/Bitmap11.png" alt=""></i>
                                    Office & Stationry
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap13.png" alt="">
                                    </i>Automotives
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
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
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap1.png" alt="">
                                    </i>Women's Fashion <i class="la la-angle-right float-right mt-2"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap2.png" alt="">
                                    </i>Home & Garden
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap3.png" alt="">
                                    </i>Home Furniture
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap4.png" alt="">
                                    </i>Shoes, Bags & Accessories
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap5.png" alt="">
                                    </i>Toys, Mother & Kid
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap6.png" alt=""></i>Moblie and
                                    Electronics
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap7.png" alt="">
                                    </i>Beauty & Health
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap8.png" alt=""></i>Men's Fashion
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap9.png" alt="">
                                    </i>Watches & Accessories
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap10.png" alt="">
                                    </i>Sports & Outdoors
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i"><img class="style-img" src="/images/Bitmap11.png" alt=""></i>
                                    Office & Stationry
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap13.png" alt="">
                                    </i>Automotives
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="mb-modal-auth">
        <div class="title-mb-menu">
            <a href="#" class="logo"><img src="/images/logo/weshop-01.png" alt="" style="height: 30px"></a>
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
                <a href="#">Daily deal</a>
            </li>
            <li>
                <a href="#">Thương hiệu nổi tiếng</a>
            </li>
            <li>
                <a href="#">Đồng hồ</a>
            </li>
            <li>
                <a href="#">Nước hoa</a>
            </li>
            <li>
                <a href="#">Nước hoa</a>
            </li>
            <li>
                <i class="la la-hand-o-right"></i>
                <a href="#">Dùng thử dịch vụ Prime</a>
            </li>
        </ul>
    </div>
</div>