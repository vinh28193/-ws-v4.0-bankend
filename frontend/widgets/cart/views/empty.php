<?php

$homeUrl = Yii::$app->homeUrl;
$js = <<<JS
    $(document).ready(function() {
    
});
JS;


?>

<div class="cart-content text-center pt-5 pb-5">
    <img src="images/icon/014-eraser.png" alt="">
    <div class="content-notifi mt-3" style="width: 30%;margin: auto; font-size: 16px">
        <span><?= Yii::t('frontend','Cart Empty') ?></span></br>
        <span><?= Yii::t('frontend','Lest add product to cart!') ?></span>
    </div>
</div>