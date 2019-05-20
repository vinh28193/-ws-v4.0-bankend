<?php

use frontend\modules\account\views\widgets\HeaderContentWidget;


$this->title = 'Lịch sử giao dịch';
echo HeaderContentWidget::widget(['title' => 'Lịch sử giao dịch','stepUrl' => ['Giao dịch' => 'my-weshop/wallet/history']]);
?>