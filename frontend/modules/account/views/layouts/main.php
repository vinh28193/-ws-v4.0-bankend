<?php
/* @var $this \yii\web\View */

/* @var $content string */

use common\components\cart\CartManager;
use frontend\modules\account\assets\UserBackendAsset;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

UserBackendAsset::register($this);
$js = <<<JS
    $('#watched').on('click',function(event) {
        event.preventDefault();
        var uri = $(this).data('url');
        console.log(uri);
        load(uri);
    });
JS;
$this->registerJs($js);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
$userID = Yii::$app->user->getId();
?>
<div class="wrapper backend" style="padding-top: 0">
    <?= \frontend\widgets\layout\HeaderWidget::widget() ?>
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
                    <span class="status online"><?= Yii::t('frontend', 'Online'); ?></span>
                </div>
                <ul id="be-menu-collapse" class="be-menu-collapse" style="margin-bottom: 0">
                    <li class="<?php if (isset($checkUrl)) {
                        if ($checkUrl == '/account/home') {
                            $active = 'active' ?> active <?php }
                    } ?>">
                        <?php echo Html::a('<span class="icon icon1"></span>' . Yii::t('frontend', 'Dashboard'), ['/account/home']); ?>
                    </li>
<!--                    <li class="accordion">
                        <a href="/my-weshop/wallet.html"><i class="icon icon2"></i> <?/*= Yii::t('frontend', 'Wallet'); */?>
                        </a>
                        <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-1"
                           aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-right"></i></a>
                        <div id="sub-1"
                             class="sub-collapse collapse <?/*= in_array('wallet', $this->params) ? ' show' : '' */?>"
                             aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                            <ul>
                                <li class="<?/*= in_array('top_up', $this->params) ? 'active' : '' */?>"><a
                                            href="/my-weshop/wallet/top-up.html"><?/*= Yii::t('frontend', 'Top up'); */?></a>
                                </li>
                                <li class="<?/*= in_array('history', $this->params) ? 'active' : '' */?>"><a
                                            href="/my-weshop/wallet/history.html"><?/*= Yii::t('frontend', 'Transaction'); */?></a>
                                </li>
                                <li class="<?/*= in_array('bank', $this->params) ? 'active' : '' */?>"><a
                                            href="#"><?/*= Yii::t('frontend', 'Account bank'); */?></a></li>
                                <li class="<?/*= in_array('withdraw', $this->params) ? 'active' : '' */?>"><a
                                            href="/my-weshop/wallet/withdraw.html"><?/*= Yii::t('frontend', 'Withdraw'); */?></a>
                                </li>
                            </ul>
                        </div>
                    </li>-->
                    <li class="accordion">
                        <a href="#"><i class="icon icon3"></i> <?= Yii::t('frontend', 'Order') ?></a>
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
                        <a class="dropdown-collapse <?php if (isset($check['status'])) { ?> <?= $collapsed[0] ?> <?php } ?><?php if (isset($checkUrl)) { ?> <?= $collapsed[0] ?> <?php } ?>"
                           data-toggle="collapse" data-target="#sub-2"
                           aria-expanded="<?php if (isset($checkUrl)) { ?> <?= $collapsed[1] ?> <?php } ?><?php if (isset($check['status'])) { ?> <?= $collapsed[1] ?> <?php } ?>"
                           aria-controls="collapseOne"><i class="la la-chevron-right"></i></a>
                        <div id="sub-2"
                             class="sub-collapse collapse <?php if (isset($check['status'])) { ?> <?= $collapsed[2] ?> <?php } ?><?php if (isset($checkUrl)) { ?> <?= $collapsed[2] ?> <?php } ?>"
                             aria-labelledby="headingOne" data-parent="#be-menu-collapse">
                            <ul class="style-nav">
                                <li class="<?php if (isset($checkUrl)) {
                                    if ($checkUrl == '/account/order') { ?> active <?php }
                                } ?><?php if (isset($checkUrl)) {
                                    if ($checkUrl == '/order') { ?> active <?php }
                                } ?>">
                                    <?php echo Html::a(Yii::t('frontend', 'All Orders List'), ['/account/order'], ['class' => 'active']); ?>
                                </li>
                                <li class="<?php if (isset($check['purchase'])) {
                                    if ($check['purchase'] == 'unpaid') { ?> active <?php }
                                } ?>">
                                    <?php echo Html::a(Yii::t('frontend', 'Unpaid'), ['/account/order?purchase=unpaid']); ?>
                                </li>
                                <li class="<?php if (isset($check['purchase'])) {
                                    if ($check['purchase'] == 'paid') { ?> active <?php }
                                } ?>">
                                    <?php echo Html::a(Yii::t('frontend', 'Paid'), ['/account/order?purchase=paid']); ?>
                                </li>
<!--                                <li class="--><?php //if (isset($check['status'])) {
//                                    if ($check['status'] == 'PURCHASED') { ?><!-- active --><?php //}
//                                } ?><!--">-->
<!--                                    --><?php //echo Html::a(Yii::t('frontend', 'Purchased'), ['/account/order?status=PURCHASED']); ?>
<!--                                </li>-->
<!--                                <li class="--><?php //if (isset($check['status'])) {
//                                    if ($check['status'] == 'STOCKIN_US') { ?><!-- active --><?php //}
//                                } ?><!--">-->
<!--                                    --><?php //echo Html::a(Yii::t('frontend', 'Stock in us'), ['/account/order?status=STOCKIN_US']); ?>
<!--                                </li>-->
<!--                                <li class="--><?php //if (isset($check['status'])) {
//                                    if ($check['status'] == 'STOCKIN_LOCAL') {
//                                        $active = 'active' ?><!-- active --><?php //}
//                                } ?><!--">-->
<!--                                    --><?php //echo Html::a(Yii::t('frontend', 'Stock in local'), ['/account/order?status=STOCKIN_LOCAL']); ?>
<!--                                </li>-->
<!--                                <li class="--><?php //if (isset($check['status'])) {
//                                    if ($check['status'] == 'AT_CUSTOMER') {
//                                        $active = 'active' ?><!-- active --><?php //}
//                                } ?><!--">-->
<!--                                    --><?php //echo Html::a(Yii::t('frontend', 'At customer'), ['/account/order?status=AT_CUSTOMER']); ?>
<!--                                </li>-->
                                <li class="<?php if (isset($check['status'])) {
                                    if ($check['status'] == 'CANCELLED') {
                                        $active = 'active' ?> active <?php }
                                } ?>">
                                    <?php echo Html::a(Yii::t('frontend', 'Cancel'), ['/account/order?status=CANCELLED']); ?>
                                </li>
                            </ul>
                        </div>
                    </li>
                 <!--   <li>
                        <?php /*echo Html::a('<span class="icon icon4"></span>' . Yii::t('frontend', 'Promotion'), ['/account/promotion-user?status=1']); */?>
                    </li>-->
<!--                    <li>-->
<!--                        <a href="#"><i class="icon icon5"></i> Weshop xu</a>-->
<!--                    </li>-->
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
                        <?php echo Html::a('<span class="icon icon6"></span>' . Yii::t('frontend', 'Account'), ['/account/customer']); ?>
                    </li>
                </ul>
                <?php
                if (!Yii::$app->user->isGuest) {

                    $menuItems[] = [

                        'label' => Yii::t('frontend', 'Logout ({username})', [
                            'username' => Yii::$app->user->identity->username
                        ]),

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
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
