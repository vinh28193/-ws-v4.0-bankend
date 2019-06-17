<?php

/* @var yii\web\View $this */
/* @var \frontend\modules\payment\Payment $payment */
?>

<div class="title">Phương thức thanh toán</div>
<div class="payment-box">
    <?php echo $payment->initPaymentView(); ?>
</div>
