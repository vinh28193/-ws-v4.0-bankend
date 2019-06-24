<?php

use common\helpers\WeshopHelper;
use yii\helpers\Html;

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
$image = \yii\helpers\ArrayHelper::getValue($product,'bigImage');
$image = $image ? $image : \yii\helpers\ArrayHelper::getValue($product,'image','/img/no_image.png');
?>

<div class="col-md-3 col-sm-6" style="padding-right: 10px;padding-left: 10px">
    <a href="<?= $url; ?>" class="item" onclick="ws.loading(true);"
       style="margin-bottom: 20px; background-color: #ffffff; padding:10px">

        <div class="thumb">
            <?php
            //            if (isset($product['end_time']) && $product['end_time'] !== null) {
            //                echo '<span class="countdown text-orange" data-toggle="countdown-time" data-timestamp="' . $product['end_time'] . '" data-prefix="" data-day="d" data-hour="h" data-minute="m" data-second="s"></span>';
            //            }
            //            ?>
            <?= Html::img($image, [
                'alt' => $product['item_name'],
                'title' => $product['item_name'],
            ])
            ?>
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
                <div class="notify">
                    <?php
                    if ($localSellprice) {
                        if ($localStartPrice && $salePercent) {
                            echo "<span class='old-price' >" . $storeManager->showMoney($localStartPrice, '') . "VND</span>";
                        } else {
                            if (isset($product['end_time']) && $product['end_time'] !== null) {
                                echo 'Còn <span class="countdown"' .
                                    ' data-toggle="countdown-time" ' .
                                    'data-timestamp="' . $product['end_time'] . '" ' .
                                    'data-prefix="" data-day="d" ' .
                                    'data-hour="h" ' .
                                    'data-minute="m" ' .
                                    'data-second="s"></span>';
                            } else {
                                echo "Nhanh tay mua ngay";
                            }
                        }
                    } else {
                        echo "Nhanh tay mua ngay";
                    }
                    ?>
                </div>
                <?php
                if ($localSellprice) {
                    echo "<strong>" . $storeManager->showMoney($localSellprice, '') . "</strong><span style='font-size: 16px;'>VNĐ</span><span> (" . $product['sell_price'] . " USD)</span>";
                } else {
                    echo Html::tag('strong', Yii::t('frontend', 'Click to see details'));
                }
                ?>
            </div>
        </div>
    </a>
</div>