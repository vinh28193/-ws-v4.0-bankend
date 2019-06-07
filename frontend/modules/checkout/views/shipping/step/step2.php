<?php

use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */
/* @var frontend\modules\payment\Payment $payment */
?>
<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span><?= Yii::t('frontend', 'Login'); ?></span></li>
        <li class="active"><i>2</i><span><?= Yii::t('frontend', 'Shipping address'); ?></span></li>
        <li><i>3</i><span><?= Yii::t('frontend', 'Payment'); ?></span></li>
    </ul>
    <div class="step-2-content row">
        <div id="step_checkout_2" class="col-md-8">
            <div class="title">Thông tin mua hàng</div>
            <div class="payment-box">

            </div>
        </div>
        <div id="step_checkout_3" class="col-md-8" style="display: none">
            <div class="title">Phương thức thanh toán</div>
            <div class="payment-box payment-step3">
                <?php echo $payment->initPaymentView(); ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php echo $this->render('_cart', ['payment' => $payment]) ?>
        </div>
    </div>
</div>
