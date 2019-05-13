<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use frontend\assets\FrontendAsset;

FrontendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Weshop Global - Homepage"/>
    <meta name='COPYRIGHT' content='&copy; Weshop Global'/>
    <meta name="robots" content="noodp,index,follow"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta property="og:title" content="website title"/>
    <meta property="og:locale" content="vi_VN"/>
    <meta property="og:url" content="website link"/>
    <meta property="og:image" content="image description"/>
    <meta property="og:description" content="website description"/>
    <meta property="og:site_name" content="website name"/>
    <meta property="fb:admins" content="Facebook Admin ID page"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <nav class="navbar navbar-default navbar-2">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="flag us"></i>
                            <span>Từ Mỹ</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <div class="title2 active">Mua hời nhất tại eBay</div>
                            <ul>
                                <li class="sub-2 open">
                                    <a href="#">Thời trang</a>
                                    <div class="sub-menu">
                                        <div class="ebay-sub-menu">
                                            <div class="left">
                                                <div class="title-box">
                                                    <div class="title">Shop eBay</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To door Delivery</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                    <a href="#"><img src="https://static-v3.weshop.com.vn/uploadImages/ea2dff/ea2dff8f1ef1dc3082523658afa31530.png" alt="" title=""/></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Đồng hồ - Trang sức</a>
                                    <div class="sub-menu">
                                        <div class="ebay-sub-menu">
                                            <div class="left">
                                                <div class="title-box">
                                                    <div class="title">Shop Test</div>
                                                    <div class="desc">Worldwide shopping, Best price, Free Shipping, To door Delivery</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="item">
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                            <div class="title">Collectibles & art</div>
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
                                                    <a href="#"><img src="https://static-v3.weshop.com.vn/uploadImages/ea2dff/ea2dff8f1ef1dc3082523658afa31530.png" alt="" title=""/></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Đồ điện tử</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Sức khỏe & làm đẹp</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Máy tính & ĐTDĐ</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Giải trí - Sở thích</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Đồ cổ & Đồ sưu tập</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Công nghiệp</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Ô tô - Xe máy</a>
                                    <div class="sub-menu"></div>
                                </li>
                            </ul>
                            <div class="see-all">
                                <a href="#">Xem toàn bộ danh mục <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="title2">Mua hời nhất tại Amazon</div>
                            <div class="title2">Top US store</div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="flag jp"></i>
                            <span>Từ Nhật</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <div class="title2 active">Amazon Japan</div>
                            <ul>
                                <li class="sub-2">
                                    <a href="#">Book</a>
                                    <div class="sub-menu">
                                        <div class="amazon-sub-content">
                                            <div class="title-box">
                                                <div class="title">Amazon Kinder & Fire</div>
                                                <div class="sub-title">Chỉ có tại Amazon</div>
                                            </div>
                                            <div class="sub-bg"><img src="https://static-v3.weshop.com.vn/uploadImages/ea2dff/ea2dff8f1ef1dc3082523658afa31530.png" alt="" title=""/></div>
                                            <div class="left">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="title-2">Amazon Video</div>
                                                        <ol>
                                                            <li><a href="#">All Videos</a></li>
                                                            <li><a href="#">Included with Prime</a></li>
                                                            <li><a href="#">Add-on Subscriptions</a></li>
                                                            <li><a href="#">Rent or Buy</a></li>
                                                            <li><a href="#">Free to watch</a></li>
                                                        </ol>
                                                        <div class="title-2">More to Explore</div>
                                                        <ol>
                                                            <li><a href="#">Video Shorts</a></li>
                                                            <li><a href="#">Style Code Live</a></li>
                                                        </ol>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="title-2">More to Explore</div>
                                                        <ol>
                                                            <li><a href="#">Video Shorts</a></li>
                                                            <li><a href="#">Style Code Live</a></li>
                                                        </ol>
                                                        <div class="title-2">Amazon Video</div>
                                                        <ol>
                                                            <li><a href="#">All Videos</a></li>
                                                            <li><a href="#">Included with Prime</a></li>
                                                            <li><a href="#">Add-on Subscriptions</a></li>
                                                            <li><a href="#">Rent or Buy</a></li>
                                                            <li><a href="#">Free to watch</a></li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="right">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Digital & Prime Music</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Amazon Photo & Drive</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Kindle E-readers & Books</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Fire Tablets</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Fire TV</a>
                                    <div class="sub-menu"></div>
                                </li>
                                <li class="sub-2">
                                    <a href="#">Echo & Alexa</a>
                                    <div class="sub-menu"></div>
                                </li>
                            </ul>
                            <div class="see-all">
                                <a href="#">Xem toàn bộ danh mục <i class="fa fa-long-arrow-right pull-right"></i></a>
                            </div>
                            <div class="title2">Top Japan store</div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="flag uk"></i>
                            <span>Từ Anh</span>
                        </a>
                        <div class="dropdown-menu animated fadeIn">
                            <a href="#">Gửi link báo giá từ Anh</a>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a href="#">Blog</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Mua hộ
                    </a>
                    <div class="dropdown-menu animated fadeIn">
                        <div class="buyship">
                            <div class="left">
                                <ul class="timeline">
                                    <li>
                                        <i class="buyship-ico ico1"></i>
                                        <div class="step">Bước 1</div>
                                        <p>Chọn hàng hoặc gửi link muốn mua</p>

                                    <li>
                                        <i class="buyship-ico ico2"></i>
                                        <div class="step">Bước 2</div>
                                        <p>Thanh toán đơn hàng</p>

                                    <li>
                                        <i class="buyship-ico ico3"></i>
                                        <div class="step">Bước 3</div>
                                        <p>Weshop hỗ trợ mua hàng, kiểm tra hàng</p>

                                    <li>
                                        <i class="buyship-ico ico4"></i>
                                        <div class="step">Bước 4</div>
                                        <p>Weshop hỗ trợ chuyển hàng về tận tay bạn</p>
                                    </li>
                                </ul>
                                <div class="text-center">
                                    <a href="#" class="btn btn-primary">Đi đến gửi link báo giá sản phẩm</a>
                                </div>
                            </div>
                            <div class="right">
                                <div class="title">Dịch vụ mua hộ</div>
                                <div class="sub-title">Nhanh hơn - Rẻ hơn - An toàn hơn</div>
                                <ol>
                                    <li><span>Vận chuyển rẻ, chỉ 14 ngày</span></li>
                                    <li><span>Miễn thủ tục hải quan</span></li>
                                    <li><span>Bảo hiểm rủi ro hàng hóa</span></li>
                                    <li><span>Mua bất cứ site nào và Không cần dùng tới tài khoản Paypal, thẻ tín dụng</span></li>
                                </ol>
                                <a href="#" class="btn btn-block">Xem các địa chỉ mua sắm</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Ship hộ
                    </a>
                    <div class="dropdown-menu animated fadeIn">
                        <div class="buyship">
                            <div class="left">
                                <ul class="timeline">
                                    <li>
                                        <i class="buyship-ico ico5"></i>
                                        <div class="step">Bước 1</div>
                                        <p>Đăng ký tài khoản Weshop Có ngay địa chỉ riêng tại Mỹ!</p>

                                    <li>
                                        <i class="buyship-ico ico1"></i>
                                        <div class="step">Bước 2</div>
                                        <p>Tự mua tại bất kỳ website nào và chuyển hàng tới địa chỉ Mỹ của bạn.</p>

                                    <li>
                                        <i class="buyship-ico ico3"></i>
                                        <div class="step">Bước 3</div>
                                        <p>Weshop hỗ trợ nhận hàng, kiểm hàng và chuyển quốc tế</p>

                                    <li>
                                        <i class="buyship-ico ico4"></i>
                                        <div class="step">Bước 4</div>
                                        <p>Weshop hỗ trợ chuyển hàng tới tận tay bạn và thu tiền dịch vụ</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="right">
                                <div class="title">Dịch vụ ship hộ</div>
                                <div class="sub-title">Rẻ hơn 30% - An toàn - Đảm bảo</div>
                                <ol>
                                    <li><a href="#">Miễn phí gom hàng</a></li>
                                    <li><span>Miễn phí gói lại hàng</span></li>
                                    <li><span>Miễn phí kiểm tra hàng</span></li>
                                    <li><span>Miễn phí lưu kho 14 ngày</span></li>
                                    <li><span>Và nhiều dịch vụ khác</span></li>
                                </ol>
                                <button type="button" class="btn ws-btn btn-block" data-toggle="modal" data-target="#wood-alert">Yêu cầu ship hàng</button>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <?php if (Yii::$app->user->isGuest) {
                        echo Html::a('<i class="nav-ico user"></i>', ['/secure/login']);
                    }else {
                        echo Html::a('<i class="nav-ico user"></i> (' . Yii::$app->user->identity->username .')', ['/secure/logout'], ['data' => ['method' => 'post']]);
                    }?>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-ico map"></i>
                    </a>
                    <div class="dropdown-menu animated fadeIn">
                        <div class="checking-order">
                            <div class="title">Kiểm tra hành trình đơn hàng</div>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Bin code">
                                <div class="input-group-append">
                                    <button class="btn btn-search" type="button"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-ico question"></i>
                    </a>
                    <div class="dropdown-menu animated fadeIn">
                        <ul>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Thanh toán như thế nào</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Chính sách hoàn trả</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Các mặt hàng cấm</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Thuế nhập khẩu</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Chính sách bảo mật</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Các điều khoản và điều kiện</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="arrow-ico"></i>
                                    <span>Liên hệ</span>
                                </a>
                            </li>
                        </ul>
                        <div class="contact">
                            <div class="contact-icon"><img src="https://weshop.com.vn/images/support-icon.png" alt="" title=""></div>
                            <div class="item">
                                <a href="mailto:support@weshop.com.vn" target="_top">support@weshop.com.vn</a>
                                <span>19006755</span>
                            </div>
                            <!--<div class="clearfix"></div>-->
                        </div>
                        <div class="support">
                            <a href="#">
                                Đến trung tâm hỗ trợ
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="nav-ico cart"></i>
                        <span class="badge">2</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="navbar-ws">
        <div class="container">
            <div class="logo">
                <span class="menu-toggle"></span>
                <a href="/" class="logo-pc">
                    <img src="/img/weshop-logo-vn.png" alt="" title=""/>
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
    </div>

    <?= $content; ?>

    <footer class="footer">
        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 item-box">
                        <div class="title">Weshop Quốc tế:</div>
                        <ul>
                            <li><a href="#"><img src="/img/flag2_vie.png" alt="" title=""/></a></li>
                            <li><a href="#"><img src="/img/flag2_us.png" alt="" title=""/></a></li>
                            <li><a href="#"><img src="/img/flag2_indo.png" alt="" title=""/></a></li>
                        </ul>
                    </div>
                    <div class="col-md-5 item-box">
                        <div class="title">Chấp nhận thanh toán:</div>
                        <ul>
                            <li><a href="#"><img src="/img/pay_master.png" alt="" title=""/></a></li>
                            <li><a href="#"><img src="/img/pay_visa.png" alt="" title=""/></a></li>
                            <li><a href="#"><img src="/img/pay_jcb.png" alt="" title=""/></a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 item-box">
                        <div class="title">Hợp tác:</div>
                        <ul>
                            <li><a href="#"><img src="/img/operate_ebay.png" alt="" title=""/></a></li>
                            <li><a href="#"><img src="/img/operate_amz.png" alt="" title=""/></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="info">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="title">Về Weshop</div>
                                <ul>
                                    <li><a href="#">Thông tin về chúng tôi</a></li>
                                    <li><a href="#">Chính sách bảo mật</a></li>
                                    <li><a href="#">Các điều khoản và điều kiện</a></li>
                                    <li><a href="#">Liên hệ</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="title">Trợ giúp khách hàng</div>
                                <ul>
                                    <li><a href="#">Thanh toán như thế nào</a></li>
                                    <li><a href="#">Chính sách hoàn trả</a></li>
                                    <li><a href="#">Các mặt hàng cấm</a></li>
                                    <li><a href="#">Thuế nhập khẩu</a></li>
                                    <li><a href="#">Chính sách bảo mật</a></li>
                                    <li><a href="#">Các điều khoản và điều kiện</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="title">Dịch vụ VAS</div>
                                <ul>
                                    <li><a href="#">Dịch vụ kiểm tra hàng hóa tại Mỹ</a></li>
                                    <li><a href="#">Hợp nhất lô hàng</a></li>
                                    <li><a href="#">Dịch vụ đóng gói lại</a></li>
                                    <li><a href="#">Bảo hiểm vận chuyển</a></li>
                                    <li><a href="#">Miễn phí 60 ngày lưu trữ</a></li>
                                    <li><a href="#">Xem toàn bộ dịch vụ VAS</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="title-register">Đăng ký để nhận tin khuyến mãi</div>
                        <div class="form-group contact">
                            <div class="input-group">
                                <input class="form-control" type="text"
                                       placeholder="Nhập email để nhận hotdeal hấp dẫn">
                                <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i
                                                    class="contact-ico"></i></button>
                                    </span>
                            </div>
                        </div>
                        <div class="sticker-bct"><a href="#" target="_blank"><img src="/img/chung_nhan_bct.png" alt=""
                                                                                  title=""/></a></div>
                        <div class="connect">
                            <span>Kết nối với Weshop qua:</span>
                            <a href="#" target="_blank"><img src="/img/social_fb.png" alt="" title=""/></a>
                            <a href="#" target="_blank"><img src="/img/social_youtube.png" alt="" title=""/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bot">
            <div class="container">
                <div class="title">Công ty cổ phần thương mại điện tử Weshop Việt Nam</div>
                <ul>
                    <li><b>Hà Nội:</b> Tầng 3, tòa nhà VTC Online số 18 đường Tam Trinh, Phường Minh Khai, Quận Hai Bà
                        Trưng, Thành phố Hà Nội, Việt Nam
                    </li>
                    <li><b>Hồ Chí Minh:</b> Lầu 3, tòa nhà VTC online, 132 Cộng Hòa, Phường 4, Q. Tân Bình</li>
                    <li><b>Mã số doanh nghiệp:</b> 0106693795 do Sở Kế hoạch và Đầu tư TP. Hà Nội cấp lần đầu ngày
                        17/11/2014
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</div>


<?php $this->endBody() ?>
<div class="loading_new" id="loading" style="display:none;">
    <div class="loading-inner-new">
        <img src="/img/gif/loading.gif">
    </div>
</div>
</body>
</html>
<?php $this->endPage() ?>
