<?php

use frontend\modules\account\views\widgets\HeaderContentWidget;

/**
 * @var $payment \frontend\modules\payment\Payment
 */
$this->title = 'Nạp tiền vào ví';
echo HeaderContentWidget::widget(['title' => 'Nạp tiền', 'stepUrl' => ['Nạp tiền' => '/my-weshop/wallet/top-up.html']]);

?>

<div class="be-box">
    <div class="be-top">
        <div class="title">Quý khách vui lòng chọn phương thức nạp tiền</div>
    </div>
    <div class="payment-box payment-step3">
        <input type="hidden" value="topup" name="type_pay">
        <?php echo $payment->initPaymentView(); ?>
    </div>
</div>