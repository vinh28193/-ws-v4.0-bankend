<?php

use frontend\widgets\layout\FooterWidget;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View *//* @var $content string */;
?>

<?php $this->beginContent("@landing/layouts/common.php"); ?>
<div class="container-fluid">
    <header id="header">
        <div class="container top-nav">
            <div class="row">
                <nav class="navbar navbar-default">
                    <div class="ws-fixed-nav">
                        <div class="container">
                            <div class="row">
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <!-- Brand and toggle get grouped for better mobile display -->
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="/">
                                                <img src="https://weshop.com.vn/images/weshop-logo.png"
                                                     alt="Weshop Logo" title="Weshop Logo">
                                            </a>
                                        </div>
                                        <div class="header-search">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-describedby="basic-addon2"
                                                       name="searchTop">
                                                <a href="javascript::void(0);" onclick="crawl.searchTop();"
                                                   class="input-group-addon">
                                                    <i class="fa fa-search ws-header-search"></i>
                                                    <i class="fa fa-usd ws-header-checkout" style="display: none;"></i>
                                                    <div class="search-loader ws-header-loading" style="display: none;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                             height="20" width="20" viewBox="0 0 75 75">
                                                            <circle cx="37.5" cy="37.5" r="33.5"
                                                                    stroke-width="6"></circle>
                                                        </svg>
                                                    </div>
                                                </a>
                                                <span class="search-brand ws-header-brand" style="display: none;">
								<img src="https://weshop.com.vn/images/ebay-2.png" alt="" title="">
							</span>
                                                <i class="fa fa-check-circle ws-header-success"
                                                   style="display: none;"></i>

                                                <div class="search-placeholder" id="aboutPlaceholder">
                                                    <strong>Bạn muốn mua sản phẩm nào?</strong><br>
                                                    <span>Nhập từ khoá hoặc dán link chi tiết của sản phẩm</span>
                                                </div>
                                                <a class="search-cancel ws-header-close" href="javascript::void(0);"
                                                   style="display: none;">
                                                    <i class="fa fa-times-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Collect the nav links, forms, and other content for toggling -->
                                        <div class="icon-navbar">
                                            <ul class="nav navbar-nav navbar-right">
                                                <!--                                                <li class="dropdown user-box user-in">-->
                                                <!--                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"-->
                                                <!--                                                       aria-haspopup="true" aria-expanded="false">-->
                                                <!--                                                        <i class="user-avatar">-->
                                                <!--                                                            <img-->
                                                <!--                                                                    src="https://static.weshop.com.vn/upload/8/s/e/h/e/q/q/9/2/1/20031813_1884928861756823_3343446603467193132_n.jpg"-->
                                                <!--                                                                    alt="Weshop"-->
                                                <!--                                                                    title="Weshop"/>-->
                                                <!--                                                        </i>-->
                                                <!--                                                    </a>-->
                                                <!--                                                    <div class="dropdown-menu animated ws-animated fadeInUpShort">-->
                                                <!--                                                        <div class="user-address">-->
                                                <!--                                                            <div class="top">-->
                                                <!--                                                                <strong>Địa chỉ tại Mỹ của bạn</strong>-->
                                                <!--                                                            </div>-->
                                                <!--                                                            <div class="user-info">-->
                                                <!--                                                                <span class="title">Họ và tên:</span>-->
                                                <!--                                                                <span class="content">-->
                                                <!--												                                                  Weshop Testt (3645912)-->
                                                <!--                                                  											</span>-->
                                                <!--                                                            </div>-->
                                                <!--                                                            <div class="user-info">-->
                                                <!--                                                                <span class="title">Địa chỉ</span>-->
                                                <!--                                                                <span class="content">-->
                                                <!--												2268 Senter Rd Ste 198											</span>-->
                                                <!--                                                            </div>-->
                                                <!--                                                            <div class="user-info">-->
                                                <!--                                                                <span class="title">Thành phố</span>-->
                                                <!--                                                                <span class="content">San Jose, California</span>-->
                                                <!--                                                            </div>-->
                                                <!--                                                            <div class="user-info">-->
                                                <!--                                                                <span class="title">Mã bưu điện</span>-->
                                                <!--                                                                <span class="content">95112-2623</span>-->
                                                <!--                                                            </div>-->
                                                <!--                                                            <div class="user-info">-->
                                                <!--                                                                <span class="title">Quốc gia</span>-->
                                                <!--                                                                <span class="content">United State</span>-->
                                                <!--                                                            </div>-->
                                                <!--                                                        </div>-->
                                                <!--                                                        <div class="acc-setting">-->
                                                <!--                                                            <a href="-->
                                                <!--/account/profile/">-->
                                                <!--                                                                <i class="icon-nav icon-gear"></i>-->
                                                <!--                                                                <span>Cài đặt tài khoản</span>-->
                                                <!--                                                            </a>-->
                                                <!--                                                        </div>-->
                                                <!--                                                        <div class="sign-out text-right">-->
                                                <!--                                                            <i class="fa fa-power-off"></i> không phải Weshop ? <a-->
                                                <!--                                                                    href="javascript:void(0);"-->
                                                <!--                                                                    onclick="user.logout();">Đăng xuất</a>-->
                                                <!--                                                        </div>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </li>-->

                                                <li class="dropdown mail-box">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                       role="button" aria-haspopup="true" aria-expanded="false">
                                                        <i class="icon-nav icon-mail"></i>
                                                    </a>
                                                    <ul class="dropdown-menu animated ws-animated fadeInUpShort">
                                                        <li>
                                                            <div class="alert alert-info" role="alert">Bạn chưa có tin
                                                                nhắn mới
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown" id="load_nottifi">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                       role="button" aria-haspopup="true" aria-expanded="false">
                                                        <i class="nav-ico noti"></i>
                                                    </a>
                                                    <div class="dropdown-menu animated fadeIn">
                                                        <ul>
                                                            <li>
                                                                <div class="alert alert-info" role="alert">Bạn chưa có
                                                                    tin nhắn mới
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="dropdown question-box">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                       role="button" aria-haspopup="true" aria-expanded="false">
                                                        <i class="icon-nav icon-question"></i>
                                                    </a>
                                                    <ul class="dropdown-menu animated ws-animated fadeInUpShort">
                                                        <li>
                                                            <a href="/faq.html?name=hoi-ve-cach-thuc-thanh-toan&amp;id=57&amp;page=2&amp;per-page=10">
                                                                <span><i class="fa fa-caret-right"></i> Thanh toán như thế nào</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/hoan-hang-cho-nguoi-ban-777.html">
                                                                <span><i class="fa fa-caret-right"></i> Chính sách bảo hành, hỗ trợ bảo hành</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/hang-han-che-nhap-hoac-cam-782.html">
                                                                <span><i class="fa fa-caret-right"></i> Các mặt hàng cấm</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/thue-phi-nhap-khau-765.html">
                                                                <span><i class="fa fa-caret-right"></i> Thuế nhập khẩu</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/chinh-sach-bao-mat-thong-tin-783.html">
                                                                <span><i class="fa fa-caret-right"></i> Chính sách bảo mật</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/dieu-khoan-su-dung-dich-vu-752.html">
                                                                <span><i class="fa fa-caret-right"></i> Các điều khoản và điều kiện</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="/helps/detail/tong-quan-ve-weshop-757.html">
                                                                <span><i class="fa fa-caret-right"></i> Liên hệ</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="contact">
                                                                <div class="contact-icon"><img
                                                                            src="https://weshop.com.vn/images/support-icon.png"
                                                                            alt="" title=""></div>
                                                                <div class="item">
                                                                    <a href="mailto:support@weshop.com.vn"
                                                                       rel="nofollow" target="_top">support@weshop.com.vn</a>
                                                                    <span>1900.6755</span>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </li>
                                                        <li class="see-more text-center">
                                                            <a href="/faq/category/cau-hoi-tong-quan-33.html">
                                                                Đến trung tâm hỗ trợ <i
                                                                        class="fa fa-long-arrow-right"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li class="dropdown cart-box">
                                                    <a href="https://weshop.com.vn/shoppingcarts.html?ref=https://weshop.com.vn/landing-page/lp-deal-ebay-178.html">
                                                        <i class="icon-nav icon-cart"></i>
                                                        <span class="badge countCart" style="display:none;">0</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div><!-- /.navbar-collapse -->
                                    </div><!-- /.container-fluid -->
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown buy-for-me">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">Mua hộ <i
                                            class="fa fa-angle-down"></i></a>
                                <div class="dropdown-menu buyforme-content animated ws-animated fadeInUpShort">
                                    <span class="arrow"></span>
                                    <div class="col-70 left">
                                        <div class="step">
                                            <ul>
                                                <li class="icon">
                                                    <div class="img"><span class="shop-icon step-1"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 1</div>
                                                        <p>Chọn hàng hoặc gửi link muốn mua</p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next1">
                                                    <i class="fa fa-circle text-active"></i>
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="shop-icon step-2"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 2</div>
                                                        <p>Thanh toán đơn hàng</p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next2">
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle text-active"></i>
                                                    <i class="fa fa-circle"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="shop-icon step-3"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 3</div>
                                                        <p>Weshop hỗ trợ mua hàng, kiểm tra hàng</p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next3">
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle text-active"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="shop-icon step-4"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 4</div>
                                                        <p>Weshop hỗ trợ chuyển hàng về tận tay bạn</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-30 right">
                                        <ul>
                                            <li>
                                                <a href="/amazon.html">
                                                    <span class="title">Shop Amazon</span>
                                                    <span class="desc">Các mặt hàng có chất lượng</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/ebay.html">
                                                    <span class="title">Shop eBay</span>
                                                    <span class="desc">Tìm sản phẩm độc đáo và siêu rẻ</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/brands.html">
                                                    <span class="title">Mua sắm tại các cửa hàng hàng đầu tại Mĩ</span>
                                                    <span class="desc">Các cửa hàng mua sắm mới</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/about-us.html">
                                                    <span class="title">Về Weshop</span>
                                                    <span class="desc">12 lợi ích khi mua sắm tại Weshop</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/paste-link.html">
                                                    <span class="title">Mua sắm trên toàn thế giới</span>
                                                    <span class="desc">Cho cuộc sống phong cách</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown ship-for-me">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">Ship hộ <i
                                            class="fa fa-angle-down"></i></a>
                                <div class="dropdown-menu shipforme-content animated ws-animated fadeInUpShort">
                                    <span class="arrow"></span>
                                    <div class="col-70 left">
                                        <div class="step">
                                            <ul>
                                                <li class="icon">
                                                    <div class="img"><span class="ship-icon step-1"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 1</div>
                                                        <p>Đăng ký tài khoản Weshop Có ngay địa chỉ riêng tại Mỹ! </p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next1">
                                                    <i class="fa fa-circle text-active"></i>
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="ship-icon step-2"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 2</div>
                                                        <p>Tự mua tại bất kỳ website nào và chuyển hàng tới địa chỉ Mỹ
                                                            của bạn.</p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next2">
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle text-active"></i>
                                                    <i class="fa fa-circle"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="ship-icon step-3"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 3</div>
                                                        <p>Weshop hỗ trợ nhận hàng, kiểm hàng và chuyển quốc tế</p>
                                                    </div>
                                                </li>
                                                <li class="next" id="next3">
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle"></i>
                                                    <i class="fa fa-circle text-active"></i>
                                                </li>
                                                <li class="icon">
                                                    <div class="img"><span class="ship-icon step-4"></span></div>
                                                    <div class="text">
                                                        <div class="text-step">Bước 4</div>
                                                        <p>Weshop hỗ trợ chuyển hàng tới tận tay bạn và thu tiền dịch
                                                            vụ</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-30 right">
                                        <ul>
                                            <li class="active"><a
                                                        href="/faq/detail/lam-the-nao-de-toi-co-duoc-dia-chi-kho-hang-weshop-786.html"><i
                                                            class="fa fa-angle-double-right"></i> Cung cấp địa chỉ của
                                                    bạn
                                                </a></li>
                                            <li><a href="/helps/detail/mien-phi-kiem-dem-hang-co-ban-775.html"><i
                                                            class="fa fa-angle-double-right"></i> Kiểm tra </a></li>
                                            <li>
                                                <a href="/helps/detail/cac-dich-vu-khac-chup-hinh-kiem-tra-chi-tiet-hang-yeu-cau-dac-biet-778.html"><i
                                                            class="fa fa-angle-double-right"></i> Dịch vụ chụp ảnh </a>
                                            </li>
                                            <li><a href="/helps/detail/mien-phi-gom-hang-772.html"><i
                                                            class="fa fa-angle-double-right"></i> Hợp nhất lô hàng </a>
                                            </li>
                                            <li><a href="/helps/detail/mien-phi-goi-lai-hang-773.html"><i
                                                            class="fa fa-angle-double-right"></i> Dịch vụ đóng gói lại
                                                </a>
                                            </li>
                                            <!--<li><a href="#"><i class="fa fa-angle-double-right"></i> Dịch vụ Đến nhận hàng</a></li>-->
                                            <li><a href="/helps/detail/bao-hiem-hang-hoa-776.html"><i
                                                            class="fa fa-angle-double-right"></i> Bảo hiểm vận chuyển
                                                </a>
                                            </li>
                                            <!--<li><a href="#"><i class="fa fa-angle-double-right"></i> Dịch vụ kê khai Hải quan</a></li>-->
                                            <li><a href="/helps/detail/mien-phi-luu-kho-toi-60-ngay-774.html"><i
                                                            class="fa fa-angle-double-right"></i> Miễn phí 60 ngày lưu
                                                    trữ
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown order-tracking">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">Kiểm tra hành trình đơn hàng <i
                                            class="fa fa-angle-down"></i></a>
                                <div class="dropdown-menu animated ws-animated fadeInUpShort">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="BIN CODE"
                                               name="checkMainOrderId">
                                    </div>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-default ws-btn"
                                                onclick="order.checkOrderMain();">Kiểm tra
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="calculature"><a
                                        href="/helps/detail/cac-dich-vu-khac-chup-hinh-kiem-tra-chi-tiet-hang-yeu-cau-dac-biet-778.html">Phí
                                    dịch vụ</a>
                            </li>
                            <li class="pricing"><a href="/size-chart.html">Bảng tra cỡ</a>
                            </li>
                            <li class="dropdown user-in ">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="user-avatar">
                                        <img src="https://weshop.com.vn/account_images/logo/no-photo.png" alt=""
                                             title="">
                                    </i>
                                </a>
                                <div class="dropdown-menu animated ws-animated fadeInUpShort">
                                    <!--                                    <div class="user-address">-->
                                    <!--                                        <div class="top">-->
                                    <!--                                            <strong>Địa chỉ tại Mỹ của bạn</strong>-->
                                    <!--                                        </div>-->
                                    <!--                                        <div class="user-info">-->
                                    <!--                                            <span class="title">Họ và tên:</span>-->
                                    <!--                                            <span class="content">-->
                                    <!--												                                                  Weshop Testt (3645912)-->
                                    <!--                                                  											</span>-->
                                    <!--                                        </div>-->
                                    <!--                                        <div class="user-info">-->
                                    <!--                                            <span class="title">Địa chỉ</span>-->
                                    <!--                                            <span class="content">-->
                                    <!--										2268 Senter Rd Ste 198									</span>-->
                                    <!--                                        </div>-->
                                    <!--                                        <div class="user-info">-->
                                    <!--                                            <span class="title">Thành phố</span>-->
                                    <!--                                            <span class="content">San Jose, California</span>-->
                                    <!--                                        </div>-->
                                    <!--                                        <div class="user-info">-->
                                    <!--                                            <span class="title">Mã bưu điện</span>-->
                                    <!--                                            <span class="content">95112-2623</span>-->
                                    <!--                                        </div>-->
                                    <!--                                        <div class="user-info">-->
                                    <!--                                            <span class="title">Quốc gia</span>-->
                                    <!--                                            <span class="content">United State</span>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                    <div class="acc-setting">
                                        <a href="https://weshop.com.vn/account/profile">
                                            <i class="icon-nav icon-gear"></i>
                                            <span>Cài đặt tài khoản</span>
                                        </a>
                                    </div>
                                    <div class="sign-out text-right">
                                        <i class="fa fa-power-off"></i> không phải ? <a href="javascript:void(0);"
                                                                                        onclick="user.logout();">Đăng
                                            xuất</a>
                                    </div>
                                </div>

                            </li>
                            <li class="dropdown mail-box">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-nav icon-mail"></i>
                                </a>
                                <ul class="dropdown-menu animated ws-animated fadeInUpShort">
                                    <li>
                                        <div class="alert alert-info" role="alert">Bạn chưa có tin nhắn mới</div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown" id="load_nottifi">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="nav-ico noti"></i>
                                </a>
                                <div class="dropdown-menu animated fadeIn">
                                    <ul>
                                        <li>
                                            <div class="alert alert-info" role="alert">Bạn chưa có tin nhắn mới</div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="dropdown question-box">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-nav icon-question"></i>
                                </a>
                                <ul class="dropdown-menu animated ws-animated fadeInUpShort">
                                    <li>
                                        <a href="/faq.html?name=hoi-ve-cach-thuc-thanh-toan&amp;id=57&amp;page=2&amp;per-page=10">
                                            <span><i class="fa fa-caret-right"></i> Thanh toán như thế nào</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/hoan-hang-cho-nguoi-ban-777.html">
                                            <span><i class="fa fa-caret-right"></i> Chính sách bảo hành, hỗ trợ bảo hành</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/hang-han-che-nhap-hoac-cam-782.html">
                                            <span><i class="fa fa-caret-right"></i> Các mặt hàng cấm</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/thue-phi-nhap-khau-765.html">
                                            <span><i class="fa fa-caret-right"></i> Thuế nhập khẩu</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/chinh-sach-bao-mat-thong-tin-783.html">
                                            <span><i class="fa fa-caret-right"></i> Chính sách bảo mật</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/dieu-khoan-su-dung-dich-vu-752.html">
                                            <span><i class="fa fa-caret-right"></i> Các điều khoản và điều kiện</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/helps/detail/tong-quan-ve-weshop-757.html">
                                            <span><i class="fa fa-caret-right"></i> Liên hệ</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="contact">
                                            <div class="contact-icon"><img
                                                        src="https://weshop.com.vn/images/support-icon.png" alt=""
                                                        title=""></div>
                                            <div class="item">
                                                <a href="mailto:support@weshop.com.vn" rel="nofollow" target="_top">support@weshop.com.vn</a>
                                                <span>1900.6755</span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                    <li class="see-more text-center">
                                        <a href="/faq/category/cau-hoi-tong-quan-33.html">
                                            Đến trung tâm hỗ trợ <i class="fa fa-long-arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown cart-box">
                                <a href="https://weshop.com.vn/shoppingcarts.html?ref=https://weshop.com.vn/landing-page/lp-deal-ebay-178.html">
                                    <i class="icon-nav icon-cart"></i>
                                    <span class="badge" style="display:none;">0</span>
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>
            </div>
        </div>
        <div class="header-navbar">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 header-logo">
                        <a href="/"><img src="https://weshop.com.vn/images/logo/weshop/weshop-logo-vn.png" alt=""></a>
                    </div>
                    <div class="col-md-6 header-search">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-describedby="basic-addon2" id="LDSearch"
                                   name="LDSearch">
                            <a href="javascript:void(0);" class="input-group-addon"
                               onclick="app.searchNew('#LDSearch',null)" data-toggle="tooltip" data-placement="top"
                               title="" style="text-decoration: none;" data-original-title="Tìm kiếm">
                                <i class="fa fa-search ws-header-search"></i>
                                <i class="fa fa-usd ws-header-checkout" style="display: none;"></i>
                                <div class="search-loader ws-header-loading" style="display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="20" width="20"
                                         viewBox="0 0 75 75">
                                        <circle cx="37.5" cy="37.5" r="33.5" stroke-width="6"></circle>
                                    </svg>
                                </div>
                            </a>
                            <span class="search-brand ws-header-brand" style="display: none;">
						<img src="https://weshop.com.vn/images/ebay-2.png" alt="" title="">
					</span>
                            <i class="fa fa-check-circle ws-header-success" style="display: none;"></i>

                            <div class="search-placeholder" id="textsearch">
                                <strong>Bạn muốn mua sản phẩm nào?</strong><br>
                                <span>Nhập từ khoá hoặc dán link chi tiết của sản phẩm</span>
                            </div>
                            <a class="search-cancel ws-header-close" href="javascript::void(0);" style="display: none;">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        </div>
                        <script>
                            function searchFunc() {
                                var x = document.getElementById("rmsearch").value;
                                document.getElementById("textsearch").style.display = "none";
                                if (x == '') {
                                    document.getElementById("textsearch").style.display = "block";
                                }
                            }
                        </script>
                    </div>
                    <div class="col-md-3">
                        <div class="header-cart">
                            <a href="https://weshop.com.vn/shoppingcarts.html?ref=https://weshop.com.vn/landing-page/lp-deal-ebay-178.html"
                               class="cart">
                                <div class="cart-text">
                                    <span class="cart-title">View shopping cart</span>

                                    <span class="text" id="cart-header" style="display:none;">0 Product(s)</span>
                                </div>
                                <div class="cart-img"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?= $content; ?>

    <?= FooterWidget::widget(); ?>

    <span class=" gotop is-animating" style="margin-right: 30px;"></span>
</div>
<?php $this->endContent(); ?>
