<?php
/**
 * @var frontend\modules\favorites\models\Favorite[] $items
 */

use common\helpers\WeshopHelper;
if(!empty($items)){
?>
<div class="title"><?php if (!is_null($items)) {
        echo Yii::t('frontend', 'Products viewed:');
    } ?></div>

    <div id="product-viewed-2" class="owl-carousel owl-theme">
    <?php
    foreach ($items  as $item) {
        try {
            $product = unserialize($item->obj_type);
            Yii::info([
                'data' =>  $product,
                'action' => 'ViewNow'
            ], __CLASS__);

            $salePercent = 0;
            if(isset($product->start_price) and $product->start_price > 0 ){
                $salePercent = round(100 * ($product->start_price - $product->sell_price) / $product->start_price);
            }
            ?>
            <div class="item-box">
                <a href="<?= \common\helpers\WeshopHelper::generateUrlDetail($product->type, $product->item_name, $product->item_id) ?>"
                   class="item">
                    <div class="thumb">
                        <img src="<?= $product->primary_images ?>" alt=""
                             alt="" title=""/>
                    </div>
                    <div class="info">
                        <!--div class="rate">
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star"></i>
                            <i class="la la-star-half-o"></i>
                            <i class="la la-star-o"></i>
                            <span>(87)</span>
                        </div-->
                        <div class="name"><?= $product->item_name ?></div>
                        <div class="price">
                            <strong><?= Yii::$app->storeManager->showMoney($product->buynow_price) ?></strong>
                            <?php if ($product->old_price) { ?>
                                <span><?= Yii::$app->storeManager->showMoney($product->old_price) ?></span>
                                <span class="sale-tag" style="display: <?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
                            <?php } // Start start_price
                            ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        } catch (Exception $exception) {
           Yii::info("Eror Sản phẩm đã xem");
           Yii::info($exception->getMessage());
        }
    }
    ?>
</div>
<?php } // $items ?>
