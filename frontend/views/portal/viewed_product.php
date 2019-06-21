<?php
/**
 * @var frontend\modules\favorites\models\Favorite[] $items
 */

use common\helpers\WeshopHelper;
if(!empty($items)){   // Todo @Huy edit thuộc tính insert lại DB + Mongodb
?>
<div class="title"><?= Yii::t('frontend', 'Sản phẩm đã xem:'); ?></div>
<div id="product-viewed-2" class="owl-carousel owl-theme">
    <?php
    foreach ($items  as $item) {

        try {
            $product = unserialize($item->obj_type);
            $salePercent = 0;
            if(isset($product['start_price'])){
                $salePercent = round(100 * ($product['start_price'] - $product['sell_price']) / $product['start_price']);
            }
            ?>
            <div class="item-box">
                <a href="<?= \common\helpers\WeshopHelper::generateUrlDetail($product['type'], $product['item_name'], $product['item_id']) ?>"
                   class="item">
                    <div class="thumb">
                        <img style="max-width: 160px" src="<?= count($product['primary_images']) ? $product['primary_images'][0]->main : '/img/no_image.png' ?>"
                             alt="" title=""/>
                    </div>
                    <div class="info">
                        <div class="rate">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star-half-o"></i>
                            <i class="la la-star-o"></i>
                            <span>(87)</span>
                        </div>
                        <div class="name"><?= $product['item_name'] ?></div>
                        <div class="price">
                            <strong><?= Yii::$app->storeManager->showMoney($product['sell_price'] * Yii::$app->storeManager->getExchangeRate()) ?></strong>
                            <?php if ($product['start_price']) { ?>
                                <span><?= Yii::$app->storeManager->showMoney($product['start_price'] * Yii::$app->storeManager->getExchangeRate()) ?></span>
                                <span class="sale-tag" style="display: <?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
                            <?php } // Start start_price
                            ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        } catch (Exception $exception) {

        }
    }
    ?>
</div>
<?php } // $items ?>
