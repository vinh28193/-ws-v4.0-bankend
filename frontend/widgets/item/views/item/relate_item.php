<?php
/**
 * @var $portal string
 * @var $product \common\products\RelateProduct
 */
use common\helpers\WeshopHelper;
?>
<div class="item-box">
    <a href="<?= WeshopHelper::generateUrlDetail(strtolower($portal), $product->title, $product->item_id) ?>"
       class="item">
        <div class="thumb">
            <img src="<?= $product->image ?>" alt=""
                 title=""/>
        </div>
        <div class="info">
            <div class="name" style="/*white-space: nowrap*/"><?= $product->title ?></div>
            <div class="price">
                <strong><?php
                    if ($product->sell_price) {
                        echo Yii::$app->storeManager->showMoney($product->sell_price * Yii::$app->storeManager->getExchangeRate());
                    } else {
                        echo Yii::t('frontend' , 'Click to view detail');
                    }
                    ?></strong>
                <?php if ($product->retail_price && $product->sell_price < $product->retail_price) { ?>
                    <span><?= Yii::$app->storeManager->showMoney($product->retail_price * Yii::$app->storeManager->getExchangeRate()); ?></span>
                    <?php
                    if ($percent > 0) {
                        echo '<span class="sale-tag">' . $percent . '% OFF</span>';
                    }
                } ?>
            </div>
        </div>
    </a>
</div>