<?php

use frontend\assets\FancyboxPlusAsset;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@frontend/views/layouts/common.php');
FancyboxPlusAsset::register($this);
$js = <<<JS
$('.slick-slider').slick({
    infinite: true,
    vertical: true,
    arrows: true,
    prevArrow: $('.slider-prev'),
    nextArrow: $('.slider-next'),
    slidesToShow: 5
});

$('#detail-big-img').ezPlus({
    zoomLensFadeIn: 500,
    gallery: 'gal1',
    imageCrossfade: true,
    scrollZoom: false,
    cursor: 'pointer'
});
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>
    <div class="keep-navbar other-page">
        <div class="container">
            <div class="cate-nav">
                <ul>
                    <li class="globe-sub">
                        <a href="#"><i class="fas fa-globe-americas"></i> <span class="text-title">Mua hàng</span></a>
                        <div class="sub-menu animated fadeIn">
                            <ul>
                                <li>
                                    <a href="#">
                                        <div class="name">Brand Fashion Outlet</div>
                                        <div class="desc">CK, Guess, Like, Adidas</div>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                    <div class="sub-menu-2">
                                        <div class="title-box">
                                            <div class="title">Amazon Kinder &amp; Fire</div>
                                            <div class="sub-title">Chỉ có tại Amazon</div>
                                        </div>
                                        <div class="ebay-sub-slider">
                                            <div id="globe-sub-slider1" class="owl-carousel owl-theme">
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="name">Health and Beauty</div>
                                        <div class="desc">Omega 369, Schiff</div>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                    <div class="sub-menu-2">
                                        <div class="title-box">
                                            <div class="title">Amazon Kinder &amp; Fire</div>
                                            <div class="sub-title">Chỉ có tại Amazon</div>
                                        </div>
                                        <div class="ebay-sub-slider">
                                            <div id="globe-sub-slider2" class="owl-carousel owl-theme">
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="name">Daily Deals</div>
                                        <div class="desc">Up to 90% Off</div>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                    <div class="sub-menu-2">
                                        <div class="title-box">
                                            <div class="title">Amazon Kinder &amp; Fire</div>
                                            <div class="sub-title">Chỉ có tại Amazon</div>
                                        </div>
                                        <div class="ebay-sub-slider">
                                            <div id="globe-sub-slider3" class="owl-carousel owl-theme">
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="name">Auction From $1</div>
                                        <div class="desc">Up to 90% Off</div>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                    <div class="sub-menu-2">
                                        <div class="title-box">
                                            <div class="title">Amazon Kinder &amp; Fire</div>
                                            <div class="sub-title">Chỉ có tại Amazon</div>
                                        </div>
                                        <div class="ebay-sub-slider">
                                            <div id="globe-sub-slider4" class="owl-carousel owl-theme">
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="item-box">
                                                        <div class="thumb">
                                                            <a href="#"><img
                                                                        src="https://i.ebayimg.com/images/g/KwgAAOSwUElcf6Wb/s-l225.webp"
                                                                        alt="" title=""/></a>
                                                            <span class="sale-tag">50% <span>Sale</span></span>
                                                        </div>
                                                        <div class="info">
                                                            <div class="name"><a href="#">Luggage Tags Labels Strap Name
                                                                    Addres......</a></div>
                                                            <div class="old-price">2.400.000đ</div>
                                                            <div class="price">1.200.000đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="brand">
                                    <a href="#">Mua hàng <img src="/img/logo_amz.png" alt="" class="brand-logo"/><i
                                                class="fa fa-angle-right pull-right"></i></a>
                                    <div class="sub-menu-2">
                                        <div class="ebay-sub-menu amazon">
                                            <div class="left">
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
                                            <div class="right">
                                                <div class="banner-sub">
                                                    <a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/bfe161/bfe1618603f223133d2b50108bef400a.jpg"
                                                                alt="" title=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="brand">
                                    <a href="#">Mua hàng <img src="/img/logo_ebay.png" alt="" class="brand-logo"/><i
                                                class="fa fa-angle-right pull-right"></i></a>
                                    <div class="sub-menu-2">
                                        <div class="ebay-sub-menu">
                                            <div class="left">
                                                <div class="title-box">
                                                    <div class="title">Shop eBay</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To
                                                        door Delivery
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles &amp; art</div>
                                                            <ul>
                                                                <li><a href="#">Collectibles</a></li>
                                                                <li><a href="#">Antiques</a></li>
                                                                <li><a href="#">Sports memorabilia</a></li>
                                                                <li><a href="#">Art</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles &amp; art</div>
                                                            <ul>
                                                                <li><a href="#">Collectibles</a></li>
                                                                <li><a href="#">Antiques</a></li>
                                                                <li><a href="#">Sports memorabilia</a></li>
                                                                <li><a href="#">Art</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles &amp; art</div>
                                                            <ul>
                                                                <li><a href="#">Collectibles</a></li>
                                                                <li><a href="#">Antiques</a></li>
                                                                <li><a href="#">Sports memorabilia</a></li>
                                                                <li><a href="#">Art</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles &amp; art</div>
                                                            <ul>
                                                                <li><a href="#">Collectibles</a></li>
                                                                <li><a href="#">Antiques</a></li>
                                                                <li><a href="#">Sports memorabilia</a></li>
                                                                <li><a href="#">Art</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="right">
                                                <div class="banner-sub">
                                                    <a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/ec01fd/ec01fd4b01e49ba21fa11b172441b3cf.jpg"
                                                                alt="" title=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="brand">
                                    <a href="#">Top US Store</a>
                                    <div class="sub-menu-2 top-store-cate">
                                        <div class="left">
                                            <ul>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/ad3d95/ad3d9567e8d7786fea8d019598f1532b.png"
                                                                alt="" title=""></a></li>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/c2949d/c2949d736841865c03b7a868446ad6f4.jpg"
                                                                alt="" title=""></a></li>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/f107cc/f107cce4a576c709d237b06b6b35bf5c.png"
                                                                alt="" title=""></a></li>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/cf44ee/cf44ee9ec008a4b7eb1475b2a63a6e3d.png"
                                                                alt="" title=""></a></li>
                                            </ul>
                                        </div>
                                        <div class="right">
                                            <ul>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/194f51/194f51bfcb1dbda44432137ca79f4cd4.png"
                                                                alt="" title=""></a></li>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/24a4d9/24a4d9308ccccf7cdf183a031833f9d2.png"
                                                                alt="" title=""></a></li>
                                                <li><a href="#"><img
                                                                src="https://static-v3.weshop.com.vn/uploadImages/0f91c2/0f91c239481817bffd1865508a54deab.jpg"
                                                                alt="" title=""></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">DEAL Tốt hôm nay</a>
                    </li>
                    <li>
                        <a href="#">Đồng hồ cao cấp</a>
                    </li>
                </ul>
            </div>
            <div class="search-box">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nhập từ khóa cần tìm"/>
                        <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fas fa-search"></i></button>
                </span>
                    </div>
                </div>
                <span class="search-tag"><i class="search-icon"></i></span>
            </div>
        </div>
    </div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Weshop Global</a></li>
                <li class="breadcrumb-item"><a href="#">Shop Amazon</a></li>
                <li class="breadcrumb-item active">Tìm kiếm từ khóa “Bulova”</li>
            </ol>
        </nav>
        <?= $content; ?>
        <div class="product-viewed product-list">
            <div class="title">Sản phẩm đã xem:</div>
            <div id="product-viewed" class="owl-carousel owl-theme">
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                        <span class="sale-tag">30% OFF</span>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
                <div class="item-box">
                    <a href="#" class="item">
                        <div class="thumb">
                            <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg"
                                 alt="" title=""/>
                        </div>
                        <div class="info">
                            <div class="rate text-orange">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span>(87)</span>
                            </div>
                            <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                            <div class="price">
                                <strong>20.430.000</strong>
                                <span>6.800.000</span>
                            </div>
                            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php

$this->endContent();
