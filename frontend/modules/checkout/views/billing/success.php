<?php

/* @var yii\web\View $this */
/* @var string $code */
/* @var common\models\PaymentTransaction $paymentTransaction */
$storeManager = Yii::$app->storeManager;
/** @var  $storeManager common\components\StoreManager */

$this->title = Yii::t('frontend', 'Payment success, invoice {code}', ['code' => $code]);
?>
<style type="text/css">
    .shipping-address {
        border-radius: 3px;
        border: 1px solid #fceab9;
        background-color: #fff4d5;
        color: #de8700;
        font-size: 12px;
        line-height: 24px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4 text-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-checkout">
                        <div class="card-body">
                            <img src="/images/icon/payment_success.png" alt="<?= $this->title; ?>"
                                 title="<?= $this->title; ?>"/ style>
                            <h4 class="title"
                                style="margin-top: 1.25rem"><?= Yii::t('frontend', 'Payment success, invoice {code}', ['code' => $code]); ?></h4>
                            <p class="text-lead">
                                <?= Yii::t("frontend", 'Within 60 minutes, {name} will contact or email you.', ['name' => $storeManager->store->name]); ?>
                            </p>
                            <p class="shipping-address">
                                <strong><?= Yii::t('frontend', 'Shipping address'); ?></strong>
                                : <?php echo $paymentTransaction->transaction_customer_address; ?>
                                <br>
                                <strong><?= Yii::t('frontend', 'Estimated delivery time'); ?></strong> : 10 -15 days
                            </p>
                            <p class="text-lead">
                                <?= Yii::t('frontend', 'Please check your email regularly to update your order it to avoid unexpected occurrences.'); ?>
                            </p>
                            <p class="text-lead">
                                <?= Yii::t('frontend', 'Thank you once again for your trust in using {name}\'s  service', [
                                    'name' => $storeManager->store->name
                                ]); ?>
                            </p>
                            <a class="btn btn-primary btn-lg" href="/"
                               role="button"><?= Yii::t('frontend', 'Ok'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript:void(0);"
                       style="color: #2b96b6;"><?= Yii::t('frontend', 'If you need assistance, please contact the hotline {phone}', [
                            'phone' => '1900-636-068'
                        ]); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
