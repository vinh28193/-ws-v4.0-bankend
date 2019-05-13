<?php

use common\helpers\WeshopHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var yii\web\View $this */
/* @var string $portal */
/* @var array $product */
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
$url = WeshopHelper::generateUrlDetail($portal,$product['item_name'],$product['item_id']);
$localSellprice = $product['sell_price'] * Yii::$app->storeManager->getExchangeRate();
$localStartPrice = 0;
if($product['retail_price']){
    $localStartPrice = $product['retail_price'] * Yii::$app->storeManager->getExchangeRate();
}
$salePercent = 0;
if($product['sell_price'] && $product['retail_price'] && $product['retail_price'] > $product['sell_price']) {
    $salePercent = 100 - round(($product['sell_price']/$product['retail_price'])*100,0);
}
//print_r($product);
//die;
?>

<div class="col-md-4 col-sm-6">
    <a href="<?= $url; ?>" class="item" onclick="ws.loading(true);">
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
                <?php
                    if($localSellprice){
                        echo "<strong>".WeshopHelper::showMoney($localSellprice)."</strong>";
                        if($localStartPrice && $salePercent){
                            echo "<span>".WeshopHelper::showMoney($localStartPrice)."</span>";
                            echo "<span class='sale-tag'>".$salePercent."% OFF</span>";
                        }
                    }else{
                        echo "<strong>Nhấp vào để xem chi tiết</strong>";
                    }
                ?>
            </div>
            <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
        </div>
    </a>
</div>