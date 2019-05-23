<?php
/**
 * @var frontend\modules\favorites\models\Favorite[] $items
 */

use common\helpers\WeshopHelper;

?>
<div class="title">Sản phẩm đã xem:</div>
<div id="product-viewed-2" class="owl-carousel owl-theme">
    <?php
    foreach ($items  as $item) {
        try {
            /** @var \common\products\BaseProduct $product */
            $product = unserialize($item->obj_type);
            $salePercent = $product->getSalePercent();
            ?>
            <div class="item-box">
                <a href="<?= \common\helpers\WeshopHelper::generateUrlDetail($product->type, $product->item_name, $product->item_id) ?>"
                   class="item">
                    <div class="thumb">
                        <img src="<?= count($product->primary_images) ? $product->primary_images[0]->thumb : '/img/no_image.png' ?>"
                             alt="" title=""/>
                    </div>
                    <div class="info">
                        <div class="rate">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <span>(87)</span>
                        </div>
                        <div class="name"><?= $product->item_name ?></div>
                        <div class="price">
                            <strong><?= WeshopHelper::showMoney($product->getLocalizeTotalPrice(), 1, '') ?><span
                                        class="currency">đ</span></strong>
                            <?php if ($product->start_price) { ?>
                                <span><?= WeshopHelper::showMoney($product->getLocalizeTotalStartPrice(), 1, '') ?><span
                                            class="currency">đ</span></span>
                                <span class="sale-tag"
                                      style="<?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
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
