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
            /** @var \common\products\BaseProduct $product */
            $product = unserialize($item->obj_type);
            try{
                if($product instanceof \common\products\BaseProduct)
                    $salePercent = $product->getSalePercent();
                else
                    $salePercent = 0;
            }catch (Exception $e){
//                print_r($product); die;
                $salePercent = 0;
            }
            ?>
            <div class="item-box">
                <a href="<?= \common\helpers\WeshopHelper::generateUrlDetail($product->type, $product->item_name, $product->item_id) ?>"
                   class="item">
                    <div class="thumb">
                        <img style="max-width: 160px" src="<?= count($product->primary_images) ? $product->primary_images[0]->main : '/img/no_image.png' ?>"
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
                        <div class="name"><?= $product->item_name ?></div>
                        <div class="price">
                            <strong><?= WeshopHelper::showMoney($product->getLocalizeTotalPrice(), 1) ?></strong>
                            <?php if ($product->start_price) { ?>
                                <span><?= WeshopHelper::showMoney($product->getLocalizeTotalStartPrice(), 1) ?></span>
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
