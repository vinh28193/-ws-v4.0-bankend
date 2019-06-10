<?php

?>
<div class="navbar-ws" xmlns="http://www.w3.org/1999/html">
    <div class="container row">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title=""/>
            </a>
        </div>
        <div class="search-box">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="searchBoxInput" id="searchBoxInput" class="form-control" value=""
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
        <div class="account-header-box">
            <i class="la la-user"></i>
            <span>
                <a href="javascript: void(0);" class="option-auth">Đăng ký / đăng nhập</a><br>
                <a href="javascript: void(0);" class="account-title">Tài khoản <i class="la la-caret-down"></i></a>
            </span>
        </div>
    </div>
    <div class="container menu-cate">
        <ul class="bars-nav">
            <li class="dropdown">
                <a id="drop1" href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                    <i class="la la-bars"></i>
                    Danh mục sản phẩm
                    <i class="la la-caret-down"></i>
                </a>
                <ul id="menu1" class="dropdown-menu category_list" role="menu" aria-labelledby="drop1" style="display: none;">
                    <li role="presentation">
                        <a href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_amazon_us.png"></span>
                            <span style="display: block; text-align: right" class="amz"><i class="la la-caret-up"></i></span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="javascript: void(0);" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                            <span style="display: block; float: left;"><img src="/img/logo_ebay.png"></span>
                            <span style="display: block; text-align: right" class="ebay"><i class="la la-caret-down"></i></span>
                        </a>
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
    <!--<nav class="navbar navbar-default navbar-2">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="flag us"></i>
                            <span>Từ Mỹ</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <div class="title2 active tab-ebay" data-toggle="tab-ebay">Mua hời nhất tại eBay</div>
                            <div class="content-tab" id="tab-ebay">
                                <ul>
                                    <? /*= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E']) */ ?>
                                </ul>
                            </div>
                            <div class="title2" data-toggle="tab-amazon">Mua hời nhất tại Amazon</div>
                            <div class="content-tab" style="display: none;" id="tab-amazon">
                                <ul>
                                    <? /*= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A']) */ ?>
                                </ul>
                            </div>
                            <div class="title2" data-toggle="tab-top-us">Top US store</div>
                            <div class="content-tab" style="display: none;" id="tab-top-us">
                                <ul>
                                    <? /*= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_US']) */ ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="flag jp"></i>
                            <span>Từ Nhật</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <div class="title2 active">Amazon Japan</div>
                            <ul>
                                <? /*= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_JP']) */ ?>
                            </ul>
                            <div class="see-all">
                                <a href="#">Xem toàn bộ danh mục <i class="fa fa-long-arrow-right pull-right"></i></a>
                            </div>
                            <div class="title2">Top Japan store</div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="flag uk"></i>
                            <span>Từ Anh</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <a href="#">Gửi link báo giá từ Anh</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>-->
</div>