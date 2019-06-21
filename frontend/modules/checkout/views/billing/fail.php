<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var string $code */
/* @var frontend\modules\payment\Payment $payment */


?>

<style type="text/css">
    .center-box {
        text-align: center;
    }
</style>
<div class="container">
    <div class="card card-checkout">
        <div class="card-body">
            <div class="center-box">
                <img src="/images/icon/payment_fail.png" alt="" title=""/>

                <p><?= Yii::t('frontend', 'Your shopping cart is empty'); ?></p>
                <a href="#" class="btn btn-continue"
                   onclick="window.location.href = '/'"><?= Yii::t('frontend', 'Continue to buy'); ?></a>
            </div>
        </div>
    </div>
</div>

