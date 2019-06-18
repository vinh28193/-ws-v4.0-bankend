<?php

use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\payment\Payment $payment */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */


$showStep = true;
$activeStep = 2;
?>

<!--<div class="card card-checkout">-->
<!--    <div class="card-header">-->
<!--        <i class="fa fa-user"></i>Thông tin người mua hàng-->
<!--    </div>-->
<!--    <div class="card-body">-->
<!--        <h5 class="card-title">Special title treatment</h5>-->
<!--        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>-->
<!--        <a href="#" class="btn btn-primary">Go somewhere</a>-->
<!--    </div>-->
<!--</div>-->
<div class="container checkout-content">
    <ul class="checkout-step">
        <li data-href=".step1"><i>1</i><span><?= Yii::t('frontend', 'Login'); ?></span></li>
        <li data-href=".step2"><i>2</i><span><?= Yii::t('frontend', 'Shipping address'); ?></span></li>
        <li data-href=".step3"><i>3</i><span><?= Yii::t('frontend', 'Payment'); ?></span></li>
    </ul>
    <div class="step-content row">
        <div class="col-md-8">
            <div class="step1">
                <?php echo $this->render('step/step1', []) ?>
            </div>
            <div class="step2 shipping-form">
                <?php echo $this->render('step/step2', [
                    'shippingForm' => $shippingForm,
                    'provinces' => $provinces
                ]) ?>
            </div>
            <div class="step3 payment-form">
                <?php echo $this->render('step/step3', [
                    'payment' => $payment,
                ]) ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php echo $this->render('cart', ['payment' => $payment]) ?>
        </div>
    </div>
</div>