<?php

use frontend\modules\account\views\widgets\HeaderContentWidget;

$this->title = 'Ví WeShop Của Tôi';
echo HeaderContentWidget::widget(['title' => 'Ví của bạn','stepUrl' => [ 'Ví của tôi' => 'my-weshop/wallet.html']]);
?>


