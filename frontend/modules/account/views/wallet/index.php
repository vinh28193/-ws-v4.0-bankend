<?php

use frontend\modules\account\views\widgets\HeaderContentWidget;

$this->title = 'Ví WeShop Của Bạn';
echo HeaderContentWidget::widget(['title' => 'Ví của bạn','stepUrl' => 'my-weshop/wallet.html']);
?>


