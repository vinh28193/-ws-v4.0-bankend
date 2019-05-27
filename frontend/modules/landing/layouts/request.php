<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $content string */

$this->beginContent("@landing/layouts/common.php");
?>

<!-- Header -->
<div class="navbar-ws">
    <div class="container">
        <div class="logo">
            <span class="menu-toggle"></span>
            <a href="/" class="logo-pc">
                <span class="tag" id="ws-tag" data-toggle="tooltip" data-placement="top" title="" data-original-title="Prime" style="top: -12.749px;">$2.99</span>
                <img src="https://weshop.com.vn/img/logo/weshop/weshop-logo.png" alt="" title="">
            </a>
            <a href="/" class="logo-mb">
                <img src="https://weshop.com.vn/img/home-new/logo-repon.png" alt="" title="">
            </a>
            <div class="mb-nav">
                <ul>
                    <li><a href="#"><i class="mb-cart"></i></a></li>
                    <li><a href="#"><i class="mb-user"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="mb-search-box">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search or Paste product URL">
                <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="search-ico"></i></button>
                    </span>
            </div>
        </div>
        <div class="produce-box">
            <ul>
                <li>
                    <span class="produce-ico product"></span>
                    <span class="text">Vận chuyển<br>rẻ từ $7/kg</span>
                </li>
                <li>
                    <span class="produce-ico plane"></span>
                    <span class="text">Không thủ tục<br>hải quan</span>
                </li>
                <li>
                    <span class="produce-ico ship"></span>
                    <span class="text">Vận chuyển <br> từ 14 ngày</span>
                </li>
                <li>
                    <span class="produce-ico insurance"></span>
                    <span class="text">Bảo hiểm<br>tới $2.000</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End header -->

<!-- Banner -->
<div class="ld-banner-slider">
    <!--    <div class="bg-shadow"></div>-->
    <div class="container">
        <div class="banner-slide">
            <!-- <div class="bg-shadow"></div> -->
            <div class="container">
                <img src="https://static-v3.weshop.com.vn/uploadImages/83b9bc/83b9bc2f39b92fc0e69314e901efca8a.jpg" alt="">            </div>
            <div class="info-form">
                <div class="info-form">
                    <form id="quote-2">
                        <div class="title">Nhập thông tin để nhận báo giá</div>
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Nhập tên của bạn">
                        </div>
                        <div class="form-group">
                            <label>số điện thoại</label>
                            <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại của bạn">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Nhập email của bạn">
                        </div>
                        <div class="form-group">
                            <label>Link sản phẩm</label>
                            <textarea class="form-control" name="link" placeholder="Nhập link sản phẩm bạn muốn mua"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Số lượng sản phẩm</label>
                            <input type="number" class="form-control" min="1" value="1" name="quantity" placeholder="Nhập số lượng sản phẩm">
                        </div>
                        <div class="btn-box">
                            <button type="button" class="btn btn-default" onclick="app.quote('quote-2');">nhận báo giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End banner -->

<!-- Help order -->
<div class="lts-content-all">
    <div class="lts-title">
        <h3>How To <b>Order</b></h3>
    </div>
    <div class="lts-content">
        <div class="container">
            <div class="lts-detail">
                <ul>
                    <li>
                        <div class="lts-other">
                            <img src="/img/pl-step1.png" alt="Access Your Shopping Website in USA">
                            <!-- <span id="lts-step1"></span> -->
                            <p>Access Your Shopping Website in USA</p>
                        </div>
                    </li>
                    <li>
                        <div class="lts-other">
                            <img src="/img/pl-step2.png" alt="Copy The Link To Product Website">
                            <!-- <span id="lts-step1"></span> -->
                            <p>Copy The Link To Product Website</p>
                        </div>
                    </li>
                    <li>
                        <div class="lts-other">
                            <img src="/img/pl-step3.png" alt="Fill in the Get Quotation Form">
                            <!-- <span id="lts-step1"></span> -->
                            <p>Fill in the Get Quotation Form</p>
                        </div>
                    </li>
                    <li>
                        <div class="lts-other">
                            <img src="/img/pl-step4.png" alt="Our Customer Service Will Contact You">
                            <!-- <span id="lts-step1"></span> -->
                            <p>Our Customer Service Will Contact You</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End help order -->

<?= $content ?>

<?php $this->endContent(); ?>
