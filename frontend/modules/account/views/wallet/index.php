<?php

use frontend\modules\account\views\widgets\HeaderContentWidget;use frontend\modules\payment\providers\wallet\WalletService;

$this->title = 'Ví WeShop Của Tôi';
echo HeaderContentWidget::widget(['title' => 'Ví của bạn','stepUrl' => [ 'Ví của tôi' => 'my-weshop/wallet.html']]);
if(WalletService::isGuest()){
    echo "Vui lòng <a>đăng nhập</a> lại để xem thông tin ví.";
}else{?>

<?php }
?>


