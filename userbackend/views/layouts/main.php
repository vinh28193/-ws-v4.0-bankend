<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use userbackend\assets\UserBackendAsset;
UserBackendAsset::register($this);
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

<div class="wrapper backend">
    <div class="navbar-2 be-header">
        <a href="#" class="be-logo"><img src="img/weshop-logo-vn.png" alt=""/></a>
        <ul class="be-nav">
            <li><span class="text-orange">50.800.000đ</span></li>
            <li>
                <a href="#">
                    <i class="icon cart"></i>
                    <i class="badge">2</i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon noti"></i>
                    <i class="badge">6</i>
                </a>
            </li>
        </ul>
    </div>
    <div class="be-container">
        <div class="be-menu">
            <div class="user-info">
                <img class="avatar" src="https://cdn4.iconfinder.com/data/icons/user-avatar-flat-icons/512/User_Avatar-04-512.png" alt=""/>
                <div class="name">Linh Kiều</div>
                <div class="email">linhkieu12@gmailinator.com</div>
                <span class="status online">Online</span>
            </div>
            <ul id="be-menu-collapse" class="be-menu-collapse">
                <li>
                    <a href="#"><i class="icon icon1"></i> Thống kê chung</a>
                </li>
                <li class="accordion">
                    <a href="#"><i class="icon icon2"></i> Quản lí tiền</a>
                    <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-1" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                    <div id="sub-1" class="sub-collapse collapse" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                        <ul>
                            <li><a href="#">Nạp tiền</a></li>
                            <li><a href="#">Giao dịch</a></li>
                            <li><a href="#">Tài khoản ngân hàng</a></li>
                            <li><a href="#">Rút tiền</a></li>
                        </ul>
                    </div>
                </li>
                <li class="accordion">
                    <a href="#"><i class="icon icon3"></i> Đơn hàng</a>
                    <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-2" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                    <div id="sub-2" class="sub-collapse collapse" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                        <ul>
                            <li><a href="#">Tất cả đơn hàng</a></li>
                            <li><a href="#">Chờ thanh toán</a></li>
                            <li><a href="#">Đã thanh toán</a></li>
                            <li><a href="#">Đã mua hàng</a></li>
                            <li><a href="#">Đã về kho US</a></li>
                            <li><a href="#">Đã về kho Việt Nam</a></li>
                            <li><a href="#">Đã giao</a></li>
                            <li><a href="#">Đã hủy</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#"><i class="icon icon4"></i> Ví voucher</a>
                </li>
                <li>
                    <a href="#"><i class="icon icon5"></i> Weshop xu</a>
                </li>
                <li class="accordion">
                    <a href="#"><i class="icon icon6"></i> Tài khoản cá nhân</a>
                    <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-3" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                    <div id="sub-3" class="sub-collapse collapse" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                        <ul>
                            <li><a href="#">Tài khoản cá nhân</a></li>
                            <li><a href="#">Sản phẩm đã lưu</a></li>
                            <li><a href="#">Cấp độ Vip</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="be-content">
            <div class="be-content-header">
                <div class="be-title">Thống kê chung</div>
                <nav aria-label="breadcrumb" class="be-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Thống kê chung</li>
                    </ol>
                </nav>
            </div>
            <?=$content;?>
        </div>
    </div>
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
                                <input class="form-control" type="text" placeholder="Nhập email để nhận hotdeal hấp dẫn">
                                <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="contact-ico"></i></button>
                                    </span>
                            </div>
                        </div>
                        <div class="sticker-bct"><a href="#" target="_blank"><img src="/img/chung_nhan_bct.png" alt="" title=""/></a></div>
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
                    <li><b>Hà Nội:</b> Tầng 3, tòa nhà VTC Online số 18 đường Tam Trinh, Phường Minh Khai, Quận Hai Bà Trưng, Thành phố Hà Nội, Việt Nam</li>
                    <li><b>Hồ Chí Minh:</b> Lầu 3, tòa nhà VTC online, 132 Cộng Hòa, Phường 4, Q. Tân Bình</li>
                    <li><b>Mã số doanh nghiệp:</b> 0106693795 do Sở Kế hoạch và Đầu tư TP. Hà Nội cấp lần đầu ngày 17/11/2014</li>
                </ul>
            </div>
        </div>
    </footer>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
