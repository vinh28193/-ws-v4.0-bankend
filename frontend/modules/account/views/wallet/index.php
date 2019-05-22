<?php

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\helpers\ArrayHelper;

/**
 * @var $wallet array
 */
$this->title = 'Ví WeShop Của Tôi';
$this->params = ['wallet'];
echo HeaderContentWidget::widget(['title' => 'Ví của bạn','stepUrl' => [ 'Ví của tôi' => 'my-weshop/wallet.html']]);
if(WalletService::isGuest()){
    echo "Vui lòng <a href='javascript:void(0);' onclick='$(\"#loginWallet\").modal()'>đăng nhập</a> lại để xem thông tin ví.";
}else{?>
    <div class="be-box">
        <div class="be-top">
            <div class="title">Thông tin số dư tài khoản</div>
        </div>
        <div class="be-body account-info" style="min-height: auto">
            <div class="row">
                <div class="col-md-4">
                    <div>Số dư tài khoản</div>
                    <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'current_balance')) ?></b>
                </div>
                <div class="col-md-4">
                    <div>Số dư đóng băng</div>
                    <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'freeze_balance')) ?></b>
                </div>
                <div class="col-md-4">
                    <div>Số dư khả dụng</div>
                    <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'usable_balance')) ?></b>
                </div>
            </div>
        </div>
    </div>
<?php }
?>


