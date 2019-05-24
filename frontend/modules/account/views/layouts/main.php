<?php
/* @var $this \yii\web\View */

/* @var $content string */

use common\components\cart\CartManager;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\modules\account\assets\UserBackendAsset;

use yii\bootstrap\Nav;

use yii\bootstrap\NavBar;
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<?php
    $check = Yii::$app->getRequest()->getQueryParams();
    $checkUrl = Yii::$app->getRequest()->url;
?>
<div class="wrapper backend">
    <div class="navbar-2 be-header">
        <a href="/" class="be-logo"><img src="/img/weshop-logo-vn.png" alt=""/></a>
        <ul class="be-nav">
            <?php if (Yii::$app->user->isGuest) { ?>
                <li>
                    <?php echo Html::a('Signup', ['/secure/signup']);?>
                </li>
                <li>
                    <?php echo Html::a('Login', ['/secure/login']);?>
                </li>
            <?php } else { ?>
                <li><span class="text-orange"><?=
                        \common\helpers\WeshopHelper::showMoney(
                            ArrayHelper::getValue(ArrayHelper::getValue((new WalletService())->detailWalletClient(),'data'),'current_balance',0)
                        ); ?></span></li>
                <li>
                    <a href="/my-cart.html">
                        <i class="icon cart"></i>
                        <i class="badge"><?= (new CartManager())->countItems() ?></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon noti"></i>
                        <i class="badge">6</i>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="be-container">
        <?php if (!Yii::$app->user->isGuest) { ?>
            <div class="be-menu">
                <div class="user-info">
                    <?php
                    if (Yii::$app->user->getIdentity()) {
                        ?>
                        <img class="avatar" src="<?= Yii::$app->user->getIdentity()->avatar ?>" alt=""/>
                        <div class="name"><?= Yii::$app->user->getIdentity()->last_name ?></div>
                        <div class="email"><?= Yii::$app->user->getIdentity()->email ?></div>
                    <?php } else { ?>
                        <img class="avatar"
                             src="https://cdn4.iconfinder.com/data/icons/user-avatar-flat-icons/512/User_Avatar-04-512.png"
                             alt=""/>
                        <div class="name"></div>
                        <div class="email"></div>
                    <?php } ?>
                    <span class="status online">Online</span>
                </div>
                <ul id="be-menu-collapse" class="be-menu-collapse" style="margin-bottom: 0">
                    <li class="<?php if (isset($checkUrl)) { if ($checkUrl == '/account/home') { $active = 'active'?> active <?php }}?>">
                        <?php echo Html::a('<span class="icon icon1"></span>Thống kê Chung', ['/account/home']);?>
                    </li>
                    <li class="accordion">
                        <a href="/my-weshop/wallet.html"><i class="icon icon2"></i> Quản lí tiền</a>
                        <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-1" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                        <div id="sub-1" class="sub-collapse collapse <?= in_array('wallet',$this->params) ? ' show' : '' ?>" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                            <ul>
                                <li class="<?= in_array('top_up',$this->params) ? 'active' : '' ?>"><a href="/my-weshop/wallet/top-up.html">Nạp tiền</a></li>
                                <li class="<?= in_array('history',$this->params) ? 'active' : '' ?>"><a href="/my-weshop/wallet/history.html">Giao dịch</a></li>
                                <li class="<?= in_array('bank',$this->params) ? 'active' : '' ?>"><a href="#">Tài khoản ngân hàng</a></li>
                                <li class="<?= in_array('withdraw',$this->params) ? 'active' : '' ?>"><a href="/my-weshop/wallet/withdraw.html">Rút tiền</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="accordion">
                        <a href="#"><i class="icon icon3"></i> Đơn hàng</a>
                        <?php
                        if (isset($checkUrl)) {
                            if ($checkUrl == '/account/order') {
                                $collapsed = array('collapsed', 'true', 'show');
                            } else {
                                $collapsed = ['a1', 'a2', 'a3'];
                            }
                        }
                        if (isset($check['status'])) {
                            if ($check['status'] === 'SUPPORTING' || $check['status'] === 'READY2PURCHASE' || $check['status'] === 'PURCHASED' || $check['status'] === 'STOCKIN_US' || $check['status'] === 'STOCKIN_LOCAL' || $check['status'] === 'AT_CUSTOMER' || $check['status'] === 'CANCEL') {
                                $collapsed = array('collapsed', 'true', 'show');
                            } else {
                                $collapsed = ['a1', 'a2', 'a3'];
                            }
                        }
                        if (isset($check['purchase'])) {
                            if ($check['purchase'] === 'paid' || $check['purchase'] === 'unpaid') {
                                $collapsed = array('collapsed', 'true', 'show');
                            } else {
                                $collapsed = ['a1', 'a2', 'a3'];
                            }
                        }
                        ?>
                        <a class="dropdown-collapse <?php if (isset($check['status'])){?> <?=$collapsed[0]?> <?php } ?><?php if (isset($checkUrl)){?> <?=$collapsed[0]?> <?php } ?>" data-toggle="collapse" data-target="#sub-2" aria-expanded="<?php if (isset($checkUrl)){?> <?=$collapsed[1]?> <?php } ?><?php if (isset($check['status'])){?> <?=$collapsed[1]?> <?php } ?>" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                        <div id="sub-2" class="sub-collapse collapse <?php if (isset($check['status'])){?> <?=$collapsed[2]?> <?php } ?><?php if (isset($checkUrl)){?> <?=$collapsed[2]?> <?php } ?>" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                            <ul class="style-nav">
                                <li class="<?php if (isset($checkUrl)) { if ($checkUrl == '/account/order') { ?> active <?php }}?><?php if (isset($checkUrl)) { if ($checkUrl == '/order') { ?> active <?php }}?>">
                                    <?php echo Html::a('Tất cả các đơn', ['/account/order'],['class' => 'active']); ?>
                                </li>
                                <li class="<?php if (isset($check['purchase'])) { if ($check['purchase'] == 'unpaid') { ?> active <?php }}?>">
                                    <?php echo Html::a('Chưa Thanh Toán', ['/account/order?purchase=unpaid']);?>
                                </li>
                                <li class="<?php if (isset($check['purchase'])) { if ($check['purchase'] == 'paid') { ?> active <?php }}?>">
                                    <?php echo Html::a('Đã thanh toán', ['/account/order?purchase=paid']);?>
                                </li>
                                <li class="<?php if (isset($check['status'])) { if ($check['status'] == 'PURCHASED') { ?> active <?php }}?>">
                                    <?php echo Html::a('Đã mua hàng', ['/account/order?status=PURCHASED']);?>
                                </li>
                                <li class="<?php if (isset($check['status'])) { if ($check['status'] == 'STOCKIN_US') { ?> active <?php }}?>">
                                    <?php echo Html::a('Đã về kho US', ['/account/order?status=STOCKIN_US']);?>
                                </li>
                                <li class="<?php if (isset($check['status'])) { if ($check['status'] == 'STOCKIN_LOCAL') { $active = 'active'?> active <?php }}?>">
                                    <?php echo Html::a('Đã về kho Việt Nam', ['/account/order?status=STOCKIN_LOCAL']);?>
                                </li>
                                <li class="<?php if (isset($check['status'])) { if ($check['status'] == 'AT_CUSTOMER') { $active = 'active'?> active <?php }}?>">
                                    <?php echo Html::a('Đã giao', ['/account/order?status=AT_CUSTOMER']);?>
                                </li>
                                <li class="<?php if (isset($check['status'])) { if ($check['status'] == 'CANCELLED') { $active = 'active'?> active <?php }}?>">
                                    <?php echo Html::a('Đã hủy', ['/account/order?status=CANCELLED']);?>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <?php echo Html::a('<span class="icon icon4"></span>Ví voucher', ['/account/promotion-user?status=1']);?>
                    </li>
                    <li>
                        <a href="#"><i class="icon icon5"></i> Weshop xu</a>
                    </li>
                    <?php
                    if (isset($checkUrl)) {
                        if ($checkUrl == '/account/customer' || $checkUrl == '/my-weshop/customer/saved.html' || $checkUrl == '/my-weshop/customer/vip.html') {
                            $collapsed1 = array('collapsed', 'true', 'show');
                        } else {
                            $collapsed1 = ['a1', 'a2', 'a3'];
                        }
                    }
                    ?>
                    <li class="accordion">
                        <a href="#"><i class="icon icon6"></i> Tài khoản cá nhân</a>
                        <a class="dropdown-collapse <?php if (isset($checkUrl)){ if ($checkUrl == '/account/customer' || $checkUrl == '/my-weshop/customer/saved.html' || $checkUrl == '/my-weshop/customer/vip.html') {?> <?=$collapsed1[0]?> <?php }} ?>" data-toggle="collapse" data-target="#sub-3" aria-expanded="<?php if (isset($checkUrl)){if ($checkUrl == '/customer' || $checkUrl == '/my-weshop/customer/saved.html' || $checkUrl == '/my-weshop/customer/vip.html') {?> <?=$collapsed1[1]?> <?php } } ?>" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                        <div id="sub-3" class="sub-collapse collapse <?php if (isset($checkUrl)) { if ($checkUrl == '/account/customer' || $checkUrl == '/my-weshop/customer/saved.html' || $checkUrl == '/my-weshop/customer/vip.html') {?> <?=$collapsed1[2]?> <?php } } ?>" aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                            <ul>
                                <li class="<?php if (isset($checkUrl)) { if ($checkUrl == '/account/customer') { $active = 'active'?> active <?php }}?>">
                                    <?php echo Html::a('Tài khoản cá nhân', ['/account/customer']);?>
                                </li>
<!--                                <li class="--><?php //if (isset($checkUrl)) { if ($checkUrl == '/my-weshop/customer/saved.html') {?><!-- active --><?php //}}?><!--">-->
<!--                                    --><?php //echo Html::a('Sản phẩm đã lưu', ['/account/customer/saved']);?>
<!--                                </li>-->
<!--                                <li>-->
<!--                                <li class="--><?php //if (isset($checkUrl)) { if ($checkUrl == '/my-weshop/customer/vip.html') {?><!-- active --><?php //}}?><!--">-->
<!--                                    --><?php //echo Html::a('Cấp độ Vip', ['/account/customer/vip']);?>
<!--                                </li>-->
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <?php
                if (!Yii::$app->user->isGuest) {

                    $menuItems[] = [

                        'label' => ' Đăng xuất (' . Yii::$app->user->identity->username . ')',

                        'url' => ['/secure/logout'],

                        'linkOptions' => ['data-method' => 'post']

                    ];
                }
                echo Nav::widget([

                    'options' => ['class' => 'be-menu-collapse'],

                    'items' => $menuItems,

                ]);

                ?>
            </div>
        <?php } ?>
        <div class="be-content">
            <?= $content; ?>
        </div>
    </div>
    <?= \frontend\widgets\layout\FooterWidget::widget() ?>
</div>


<!-- Modal login waller -->
<div class="modal" id="loginWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Đăng nhập ví</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <i class="icon password"></i>
                    <input type="password" name="passwordWallet" class="form-control" placeholder="Mật khẩu">
                    <label style="color: red" id="ErrorPasswordWallet"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="loginWallet()">Đăng nhập</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
