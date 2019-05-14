<?php

/* @var yii\web\View $this */
/* @var frontend\modules\checkout\models\ShippingForm $shippingForm */
/* @var frontend\modules\checkout\Payment $payment */
?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span>Đăng nhập</span></li>
        <li><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li class="active"><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-2-content row">
        <div class="col-md-8">
            <div class="title">Phương thức thanh toán</div>
            <div class="payment-box payment-step3">
                <?php echo $payment->initPaymentView(); ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php echo $this->render('_cart',['payment' => $payment]) ?>
        </div>
    </div>
</div>
