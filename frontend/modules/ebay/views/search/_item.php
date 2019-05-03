<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $product array */

/*
 * Todo price, price range
 * Todo rate
 */
/**
 * Todo item url
 * use UrlRule
 * @see \yii\web\UrlRule
 * @see \yii\web\UrlRuleInterface
 */
$url = Yii::$app->getUrlManager()->createUrl([
    'ebay/item',
    'id' => $product['item_id']
]);
?>

<div class="col-md-4 col-sm-6">
    <a href="<?=$url;?>" class="item">
        <div class="thumb">
            <?= Html::img($product['image'], [
                'alt' => $product['item_name'],
                'title' => $product['item_name'],
            ]) ?>
        </div>
        <div class="info">
            <div class="rate text-orange">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
                <span>(87)</span>
            </div>
            <?= Html::tag('div', $product['item_name'], ['class' => 'name']) ?>
            <div class="price">
                <strong>20.430.000</strong>
                <span>6.800.000</span>
            </div>
            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
        </div>
    </a>
</div>