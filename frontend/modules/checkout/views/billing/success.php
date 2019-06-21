<?php

/* @var yii\web\View $this */
/* @var string $code */

$storeManager = Yii::$app->storeManager;
/** @var  $storeManager common\components\StoreManager */

?>

<div class="jumbotron">
    <h1 class="display-4"><?= Yii::t('frontend', 'Thank you for purchase, your invoice {code}', ['code' => $code]); ?></h1>
    <p class="lead">
        <?= Yii::t("frontend", "Thank you for using {storeName}'s household purchase service. We just sent an invoice to your email address. Please check your email regularly to update your order it to avoid unexpected occurrences.", ['storeName' => $storeManager->store->country_name]); ?>
    </p>
    <p class="lead">
        Thời gian giao hàng dự kiến từ 26-06-19 ~ 28-06-19.
    </p>
    <hr class="my-4">
    <p><?= Yii::t('frontend', "Thank you once again for your trust in using {storeName}'s household purchase service.", ['storeName' => $storeManager->store->country_name]); ?></p>
    <a class="btn btn-primary btn-lg" href="/" role="button">Ok</a>
</div>
