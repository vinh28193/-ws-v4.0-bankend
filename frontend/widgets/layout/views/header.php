<?php

?>
<div class="navbar-ws" xmlns="http://www.w3.org/1999/html">
    <div class="container row">
        <div class="logo col-md-2">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title="" width ="175px"/ >
            </a>
        </div>
        <div class="search-box col-md-5">
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
        <div class="cart-header-box col-md-2">
            <i class="la la-shopping-cart"></i>
            <span class="label-cart">Giỏ hàng (10)</span>
        </div>
        <div class="account-header-box dropdown style-account col-md-3" style="width: 150px">
            <a class="bg-white" id="dropAcount" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
               aria-expanded="false">
                <span class="row">
                    <span class="col-md-3 m-0 pr-0 pt-1 float-right">
                        <i class="la la-user" style="/*font-size: 40px*/"></i>
                    </span>
                    <span class="col-md-9 m-0">
                        <span href="javascript: void(0);" class="option-auth">Đăng ký / đăng nhập</span><br>
                        <span class="account-title">Tài khoản</span>
                    </span>
                </span>
            </a>
            <ul id="menuAccount" class="dropdown-menu category_list category_account" role="menu" aria-labelledby="dropAcount"
                style="display: none; border-right: 1px solid gray">
            </ul>
        </div>
    </div>
    <div class="container menu-cate">
        <ul class="bars-nav">
            <li class="dropdown active mr-1">
                <a id="drop1" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
                   aria-expanded="false">
                    <i class="la la-bars"></i>
                    Danh mục sản phẩm
                    <i class="la la-caret-down"></i>
                </a>
                <ul id="menu1" class="dropdown-menu category_list" role="menu" aria-labelledby="drop1"
                    style="display: none; border-right: 1px solid gray ">
                    <li role="presentation">
                        <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <span style="display: block; float: left;"><img src="/img/logo_amazon_us.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="amz"><i
                                        class="la la-caret-up"></i></span>
                        </a>
                        <ul class="style-ull">
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap1.png" alt="">
                                    </i>Women's Fashion <i class="la la-angle-right float-right mt-2"></i></a>
                                <div class="sub-menu-2">
                                    <div class="ebay-sub-menu ebay">
                                        <div class="row">
                                            <div class="col-md-7 pl-4">
                                                <div class="title-box">
                                                    <div class="title">Shop Amazon</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To door Delivery</div>
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
                                            <div class="col-md-5">
                                                <div class="banner-sub">
                                                    <a href="#">
                                                        <img src="https://i.ebayimg.com/00/s/NjAwWDYwMA==/z/3v0AAOSwfVhaHfbT/$_1.JPG" alt="" title=""></a>
                                                </div>
                                            </div>
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
                                <div class="sub-menu-2 __web-inspector-hide-shortcut__">
                                    <div class="title-box">
                                        <div class="title" style="font-weight: 700"> Giá sốc mỗi ngày</div>
                                        <div class="sub-title">Hàng ngàn ưu đãi mỗi ngày</div>
                                    </div>
                                    <div class="ebay-sub-slider">
                                        <div id="main">
                                            <div class="container">
                                                <h1>Carousel with bootstrap</h1>
                                                <div id="carousel-simple" class="carousel multi-item-carousel slide col-md-6 col-md-offset-3" data-ride="carousel">

                                                    <!-- Wrapper for slides -->
                                                    <div class="carousel-inner" role="listbox">
                                                        <div class="item active">
                                                            <div class="col-md-4">
                                                                <img src="media/products/2.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/3.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/17.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/24.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item ">
                                                            <div class="col-md-4">
                                                                <img src="media/products/12.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/13.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/21.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/22.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item ">
                                                            <div class="col-md-4">
                                                                <img src="media/products/23.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/9.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/10.png" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="col-md-4">
                                                                <img src="media/products/11.png" alt="">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Controls -->
                                                    <a class="left carousel-control" href="#carousel-simple" role="button" data-slide="prev">
                                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="right carousel-control" href="#carousel-simple" role="button" data-slide="next">
                                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <hr class="m-0">
                    <li role="presentation">
                        <a href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button"
                           aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_ebay.png"></span>
                            <span style="display: block; text-align: right; margin-right: 8px" class="ebay"><i
                                        class="la la-caret-down"></i></span>
                        </a>
                        <ul class="style-ull">
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i"><img class="style-img"  src="/images/Bitmap1.png" alt=""></i>Women's Fashion
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                                <div class="sub-menu-2">
                                    <div class="ebay-sub-menu ebay">
                                        <div class="row">
                                            <div class="col-md-7 pl-4">
                                                <div class="title-box">
                                                    <div class="title">Shop Amazon</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To door Delivery</div>
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
                                            <div class="col-md-5">
                                                <div class="banner-sub">
                                                    <a href="#">
                                                        <img src="https://i.ebayimg.com/00/s/NjAwWDYwMA==/z/3v0AAOSwfVhaHfbT/$_1.JPG" alt="" title=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="style-i">
                                        <img class="style-img" src="/images/Bitmap2.png" alt=""></i>Home & Garden
                                    <i class="la la-angle-right float-right mt-2"></i>
                                </a>
                                <div class="sub-menu-2">
                                    <div class="ebay-sub-menu ebay">
                                        <div class="row">
                                            <div class="col-md-7 pl-4">
                                                <div class="title-box">
                                                    <div class="title">Shop Amazon</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To door Delivery</div>
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
                                            <div class="col-md-5">
                                                <div class="banner-sub">
                                                    <a href="#">
                                                        <img src="https://i.ebayimg.com/00/s/NjAwWDYwMA==/z/3v0AAOSwfVhaHfbT/$_1.JPG" alt="" title=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap3.png"
                                                                                     alt=""></i>Home Furniture <i
                                            class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap4.png"
                                                                                     alt=""></i>Shoes, Bags &
                                    Accessories <i class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap5.png"
                                                                                     alt=""></i>Toys, Mother & Kid <i
                                            class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap6.png"
                                                                                     alt=""></i>Moblie and Electronics
                                    <i class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap7.png"
                                                                                     alt=""></i>Beauty & Health <i
                                            class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap8.png"
                                                                                     alt=""></i>Men's Fashion <i
                                            class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap9.png"
                                                                                     alt=""></i>Watches & Accessories <i
                                            class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap10.png" alt=""></i>Sports
                                    & Outdoors <i class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap11.png" alt=""></i>Office
                                    & Stationry <i class="la la-angle-right float-right mt-2"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="style-i"><img class="style-img"
                                                                                     src="/images/Bitmap13.png" alt=""></i>Automotives
                                    <i class="la la-angle-right float-right mt-2"></i></a></li>
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