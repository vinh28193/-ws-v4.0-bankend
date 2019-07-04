<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var string $code */
/* @var frontend\modules\payment\Payment $payment */

$this->title = Yii::t('frontend', 'Payment failed {code}', ['code' => $code]) . ' | ' . Yii::t('frontend', 'Payment method {method}', ['method' => implode(', ', [$payment->payment_method_name, $payment->payment_provider_name])])

?>

<style type="text/css">
    .btn-continue {
        border-radius: 3px;
        border: 1px solid #d25e0d;
        background-image: linear-gradient(180deg, #ff9d17 0%, #e67424 100%);
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4 text-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-checkout">
                        <div class="card-body">
                            <img src="/images/icon/payment_fail.png" alt="" title=""/>
                            <p class="m-3"><?= Yii::t('frontend', 'There was an error in the payment process or the transaction has expired. Do
                                you want to make a payment again?') ?></p>
                            <div class="button-group">
                                <button class="btn btn-block btn-continue" data-toggle="modal"
                                        data-target="#otherMethods"><?php echo Yii::t('frontend', 'Choose other payment method'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="/" style="color: #2b96b6;">Cancel</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="otherMethods" tabindex="-1" role="dialog" aria-labelledby="otherMethodsTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="otherMethodsTitle"><?php echo Yii::t('frontend', 'Select a payment method'); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $payment; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

