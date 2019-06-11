<?php

use common\helpers\WeshopHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var yii\web\View $this */
/* @var string $portal */
/* @var array $product */
/* @var common\components\StoreManager $storeManager */
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
$url = WeshopHelper::generateUrlDetail($portal, $product['item_name'], $product['item_id']);
$localSellprice = $product['sell_price'] * $storeManager->getExchangeRate();
$localStartPrice = 0;
if ($product['retail_price']) {
    $localStartPrice = $product['retail_price'] * $storeManager->getExchangeRate();
}
$salePercent = 0;
if ($product['sell_price'] && $product['retail_price'] && $product['retail_price'] > $product['sell_price']) {
    $salePercent = 100 - round(($product['sell_price'] / $product['retail_price']) * 100, 0);
}

?>

<div class="col-md-4 col-sm-6">
    <a href="<?= $url; ?>" class="item" onclick="ws.loading(true);">

        <div class="thumb">
            <?php
            if (isset($product['end_time']) && $product['end_time'] !== null) {
                echo '<span class="countdown text-orange" data-toggle="countdown-time" data-timestamp="' . $product['end_time'] . '" data-prefix="" data-day="d" data-hour="h" data-minute="m" data-second="s"></span>';
            }
            ?>
            <?= Html::img($product['image'], [
                'alt' => $product['item_name'],
                'title' => $product['item_name'],
            ]) ?>
        </div>
        <div class="info">
            <div class="rate text-orange">
                <i class="la la-star"></i>
                <i class="la la-star"></i>
                <i class="la la-star"></i>
                <i class="la la-star-half-o"></i>
                <i class="la la-star-o"></i>
                <span>(87)</span>
            </div>
            <?= Html::tag('div', $product['item_name'], ['class' => 'name']) ?>
            <div class="price">
                <?php
                if ($localSellprice) {
                    echo "<strong>" . $storeManager->showMoney($localSellprice) . "</strong>";
                    if ($localStartPrice && $salePercent) {
                        echo "<span>" . $storeManager->showMoney($localStartPrice) . "</span>";
                        echo "<span class='sale-tag'>" . $salePercent . "% OFF</span>";
                    }
                } else {
                    echo Html::tag('strong', Yii::t('frontend', 'Click to see details'));
                }
                ?>
            </div>
        </div>
    </a>
</div>