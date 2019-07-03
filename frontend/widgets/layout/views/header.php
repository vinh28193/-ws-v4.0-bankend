<?php
$js = <<<JS
ws.reloadCartBadge();
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

$userCookie = new \common\components\UserCookies();
$userCookie->setUser();
$shipTo = Yii::t('frontend', 'Click here');
if ($userCookie->checkAddress()) {
    $array = [];
    if (($provide = $userCookie->getProvince())) {
        $shipTo = $provide->name;
    }
}
?>
<div class="navbar-ws mobile-hide style-header" id="header" xmlns="http://www.w3.org/1999/html">
    <div class="container row">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title="">
            </a>
        </div>
            <div class="shipping-header-box">
                <div class="shipping-label"><?php echo Yii::t('frontend', 'Ship to'); ?>:</div>
                <div class="shipping-address"><a onclick="ws.showModal('modal-address')" href="javascript:void(0);"><?=$shipTo;?></a><i class="la la-caret-down"></i></div>
            </div>
        <div class="search-box">
            <div class="form-group mb-0">
                <div class="input-group">
                    <input type="text" list="listSuggestSearch" autocomplete="off"
                           class="form-control searchBoxInput" <?= Yii::$app->request->get('keyword', '') ? 'value="' . Yii::$app->request->get('keyword', '') . '"' : '' ?>
                           placeholder="<?= Yii::t('frontend', 'Paste link or id product amazon.com, ebay.com at here') ?>">
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
            <a href="/my-cart.html"><span class="label-cart"><?= Yii::t('frontend', 'Cart') ?> (<span
                            class="count-cart">0</span>)</span></a>
        </div>
        <div class="account-header-box dropdown style-account">
            <a class="bg-white" id="dropAcount" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true"
               role="button"
               aria-expanded="false">
                <span class="row">
                    <span class="col-md-3 m-0 pr-0 float-right">
                        <i class="la la-user"></i>
                    </span>
                    <span class="col-md-9 m-0  pl-0">
                        <span href="javascript: void(0);"
                              class="option-auth"><?= Yii::$app->user->isGuest ? Yii::t('frontend', 'Sign Up / Login') : Yii::$app->user->getIdentity()->first_name . ' ' . Yii::$app->user->getIdentity()->last_name ?></span><br>
                        <span class="account-title"><?= Yii::t('frontend', 'Account') ?><i class="la la-caret-down"></i></span>
                    </span>
                </span>
            </a>
            <ul id="menuAccount"
                class="box-shadow dropdown-menu category_account <?= Yii::$app->user->isGuest ? '' : 'left-130' ?>"
                role="menu" aria-labelledby="dropAcount"
                style="display: none;">
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li class="card" style="border: none">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <span class="color-account"><?= Yii::t('frontend', 'You can login with') ?></span>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <button onclick="smsLogin();"
                                            class="btn btn-fb"><?= Yii::t('frontend', 'Login via SMS') ?></button>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <button class="btn btn-google" data-action="clickToLoad" data-href="/login.html"
                                            class="btn btn-info">
                                        <?= Yii::t('frontend', 'Login via Email') ?>
                                    </button>
                                </div
                            </div>
                            <hr class="color-account">
                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <span class="color-account"><?= Yii::t('frontend', 'You not have account ?') ?><a
                                                href="javascript:void (0);" data-action="clickToLoad"
                                                data-href="/signup.html"
                                                class="btn-link">&nbsp;<?= Yii::t('frontend', 'SignUp click here') ?></a></span>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } else { ?>
                    <li class="card" style="border: none">
                        <div class="card-body">
                            <div class="row" id="list-menu-account">
                                <div class="col-md-12 mb-3">
                                    <a href="javascript:void (0);" data-action="clickToLoad"
                                       data-href="/account/order"><?= Yii::t('frontend', 'My orders') ?></a>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <a href="javascript:void (0);" data-action="clickToLoad"
                                       data-href="/my-weshop.html"><?= Yii::t('frontend', 'My Account') ?></a>
                                </div>
                                <div class="col-md-12 mb-2 social-button">
                                    <a class="btn btn-info p-2" href="javascript:void (0);" data-action="clickToLoad"
                                       data-href="/logout.html"><?= Yii::t('frontend', 'Logout') ?></a>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="account-header-box dropdown style-account">
            <span class="view-exchange-rate">
                USD
            </span><br>
            <span class="view-exchange-rate">
                <?= Yii::$app->storeManager->showMoney(Yii::$app->storeManager->getExchangeRate(),$currency = null , $isRound = false) ?>
            </span>
        </div>
    </div>
    <div class="container menu-cate">
        <ul class="bars-nav bars-nav123">
            <li class="dropdown active mr-1">
                <a id="drop1" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
                   aria-expanded="false" style="display: inline-flex;">
                    <span style="padding-top: 3px;display: block;" class=""><i class="la la-bars"></i></span>
                    <span style="display: block;"><?= Yii::t('frontend', 'Categories') ?></span>
                    <span style="display: block;"><i class="la la-caret-down"></i></span>
                </a>
                <ul id="menu1" class="dropdown-menu category_list style-u" role="menu" aria-labelledby="drop1"
                    style="display: none; border-right: 1px solid gray ">
                    <li role="presentation">
                        <a data-toggle="collapse" class="toggle" id="toggle" role="button" aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_amazon_us.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="ico-dropdown amz"><i
                                        id="asd"
                                        class="la la-caret-down" style="transform: scaleY(-1);"></i></span>
                        </a>
                        <ul class="style-ull menu" id="target" style="display: block;">
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A']) ?>
                        </ul>
                    </li>
                    <hr class="m-0">
                    <li role="presentation">
                        <a href="javascript: void(0);" class="toggleEbay" data-toggle="dropdown" aria-haspopup="true"
                           role="button"
                           aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_ebay.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px"
                                  class="ico-dropdown ebay"><i id="asd1"
                                                               class="la la-caret-down"></i></span>
                        </a>
                        <ul class="style-ull" id="targetEbay">
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E']) ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#dailydeal') ?>"><?= Yii::t('frontend', 'Daily deal') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#famousbrands') ?>"><?= Yii::t('frontend', 'Famous brands') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#watch') ?>"><?= Yii::t('frontend', 'Watch') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#perfume') ?>"><?= Yii::t('frontend', 'Perfume') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#tryuseprime') ?>">
                    <i class="la la-hand-o-right"></i>
                    <?= Yii::t('frontend', 'Try use Prime') ?>
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
                    <?= Yii::t('frontend', 'Account') ?>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="checkout-order">
                    <i class="la la-shopping-cart"></i>
                    <span class="badge count-cart">0</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="mb-menu">
        <div class="title-mb-menu">
            <i class="la la-close"></i>
            <span><?= Yii::t('frontend', 'Categories') ?></span>
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
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A', 'logoMobile' => '/img/logo_amazon_us.png', 'type_monitor' => 'mobile']) ?>
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
                            <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E', 'logoMobile' => '/img/logo_ebay.png', 'type_monitor' => 'mobile']) ?>
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
                    <button onclick="smsLogin();" class="btn btn-fb"><?= Yii::t('frontend', 'Login via SMS') ?></button>
                </div>
                <div class="col-6">
                    <button class="btn btn-google" data-action="clickToLoad" data-href="/login.html"
                            class="btn btn-info">
                        <?= Yii::t('frontend', 'Login via Email') ?>
                    </button>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12"><span><?= Yii::t('frontend', 'Are you not have account?') ?></span></div>
                <div class="col-12">
                    <button class="btn btn-link" data-action="clickToLoad"
                            data-href="/signup.html"><?= Yii::t('frontend', 'Register here') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="search-box-mobile">
        <div class="form-group">
            <div class="input-group">
                <input type="text" list="listSuggestSearch" class="form-control searchBoxInput"
                       value="<?= Yii::$app->request->get('keyword', '') ?>"
                       placeholder="<?= Yii::t('frontend', 'Paste link or id product') ?>">
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
                <a href="<?= Yii::t('frontend', '#dailydeal') ?>"><?= Yii::t('frontend', 'Daily deal') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#famousbrands') ?>"><?= Yii::t('frontend', 'Famous brands') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#watch') ?>"><?= Yii::t('frontend', 'Watch') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#perfume') ?>"><?= Yii::t('frontend', 'Perfume') ?></a>
            </li>
            <li>
                <a href="<?= Yii::t('frontend', '#tryuseprime') ?>">
                    <i class="la la-hand-o-right"></i>
                    <?= Yii::t('frontend', 'Try use Prime') ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="mb-modal-checkout-order">
        <div class="mb-modal-sm-checkout">
            <div class="title-mb-menu-checkout" style="height: 68px;">
                <a href="/" class="close-checkout">
                    <span style="font-weight: 700; color: white; font-size: 17px; position: absolute; margin-top: 5px"><?= Yii::t('frontend', 'Cart') ?></span>
                </a>
                <i class="la la-close" style="float: right;padding:5px;"></i>
                <div class="clearfix"></div>
            </div>
            <div class="content-modal-auth-mb">
                <div class="row">
                    <div class="col-8 pl-0">
                        <strong><?= Yii::t('frontend', 'Product in cart') ?></strong>
                    </div>
                    <div class="col-4 pr-0 text-right">
                        <a href="javascript:void(0)"><?= Yii::t('frontend', 'Delete all') ?></a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3 m-o p-0">
                        <div class="thumb" style="height: auto;">
                            <img src="https://i.ebayimg.com/00/s/MTEyMFgxNTAw/z/oYAAAOSwgPVcrnUX/$_1.JPG"
                                 alt="MSI Radeon RX 460 DirectX 12 RX 460 2G OC 2GB 128-Bit GDDR5 PCI Express 3.0 x16"
                                 width="100%"
                                 title="MSI Radeon RX 460 DirectX 12 RX 460 2G OC 2GB 128-Bit GDDR5 PCI Express 3.0 x16">
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="info">
                            <div class="left">
                                <a href="http://weshop-v4.front-end-ws.local.vn/ebay/item/msi-radeon-rx-460-directx-12-rx-460-2g-oc-2gb-128-bit-gddr5-pci-express-30-x16-352639604796.html"
                                   target="_blank" class="name style-aa">
                                    MSI Radeon RX 460 DirectX 12 RX 460 2G OC 2GB 128-Bit GDDR5 PCI Express 3.0 x16</a>
                            </div>
                            <div class="price price-option text-danger">
                                <strong>0VND</strong>
                            </div>
                            <div class="right mt-2 stylll">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="qty form-inline quantity-option">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-secondary btn-sm button-quantity-down style-add-quantyti"
                                                            data-pjax="1" data-type="shopping"
                                                            data-parent="5d0889d5317e410bfc007089"
                                                            data-id="352639604796" data-sku="604103"
                                                            data-update="#5d0889d5317e410bfc007089" data-operator="down"
                                                            type="button">-
                                                    </button>
                                                </div>
                                                <input type="text" name="cartItemQuantity"
                                                       class="form-control style-quantity form-control-sm" value="2"
                                                       data-min="1" data-type="shopping" data-max="0"
                                                       data-parent="5d0889d5317e410bfc007089"
                                                       style="height: 30px; border-left: 1px solid #ced4da; border-right: 1px solid #ced4da"
                                                       data-id="352639604796" data-sku="604103">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary btn-sm button-quantity-up style-add-quantyti"
                                                            data-pjax="1" data-parent="5d0889d5317e410bfc007089"
                                                            data-type="shopping" data-id="352639604796"
                                                            data-sku="604103" data-operator="up" type="button">+
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right pt-1">
                                        <a href="#" class="del delete-item" data-type="shopping"
                                           data-parent="5d0889d5317e410bfc007089" data-id="352639604796"
                                           data-sku="604103" <i=""> Xóa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button class="btn style-btn1 mt-2"><span class="la la-shopping-cart float-left"
                                                              style="font-size: 1.7em;"></span><?= Yii::t('frontend', 'Check out') ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="wait-main">
</div>
<datalist id="listSuggestSearch">
</datalist>
