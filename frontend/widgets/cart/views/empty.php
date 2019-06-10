<?php

$homeUrl = Yii::$app->homeUrl;
?>

<div class="cart-content">
    <div class="title">
        <?= Yii::t('frontend', 'Your shopping cart <span>({count} items)</span>', [
            'count' => 0
        ]); ?>
    </div>
    <div class="empty-box">
        <img src="/img/cart_empty.png" alt="" title=""/>

        <p><?= Yii::t('frontend', 'Your shopping cart is empty'); ?></p>
        <a href="#" class="btn btn-continue"
           onclick="window.location.href = '/'"><?= Yii::t('frontend', 'Continue to buy'); ?></a>
    </div>
</div>
