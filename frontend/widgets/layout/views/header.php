<?php

use yii\helpers\Html;

?>
<div class="navbar-ws">
    <div class="container">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <img src="/images/logo/weshop-01.png" alt="" title=""/>
            </a>
        </div>
        <div class="produce-box">
            <ul>
                <li>
                    <span class="produce-ico icon1"></span>
                    <span class="text">Giá rẻ chỉ từ $8.5/Kg</span>
                </li>
                <li>
                    <span class="produce-ico icon2"></span>
                    <span class="text">Thủ tục trọn gói</span>
                </li>
                <li>
                    <span class="produce-ico icon3"></span>
                    <span class="text">Vận chuyển từ 14 ngày</span>
                </li>
                <li>
                    <span class="produce-ico icon4"></span>
                    <span class="text">Bảo hiểm tới $2.000</span>
                </li>
            </ul>
        </div>
    </div>
    <nav class="navbar navbar-default navbar-2">
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
                                    <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_E']) ?>
                                </ul>
                            </div>
                            <div class="title2" data-toggle="tab-amazon">Mua hời nhất tại Amazon</div>
                            <div class="content-tab" style="display: none;" id="tab-amazon">
                                <ul>
                                    <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_A']) ?>
                                </ul>
                            </div>
                            <div class="title2" data-toggle="tab-top-us">Top US store</div>
                            <div class="content-tab" style="display: none;" id="tab-top-us">
                                <ul>
                                    <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_US']) ?>
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
                                <?= \frontend\widgets\alias\TopMenuAliasWidget::widget(['type' => 'NAV_TOP_JP']) ?>
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
    </nav>
</div>