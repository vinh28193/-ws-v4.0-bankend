<?php
/**
 * @var $item \common\products\BaseProduct
 * @var $product \common\products\RelateProduct
 */

use common\helpers\WeshopHelper;
//print_r($item->relate_products);
//die;
?>
    <div  id="product-relate" class="product-viewed product-list box-shadow" style="display: <?= ($item->relate_products) ? 'block' : 'none' ?>">
        <div class="title"><?= Yii::t('frontend','Product Relate') ?>:</div>
        <div class="owl-carousel owl-theme">
            <?php
            if($item->relate_products) {
                foreach ($item->relate_products as $product) {
                    $percent = $product->retail_price && $product->sell_price ? round((($product->retail_price - $product->sell_price) / $product->retail_price) * 100, 0) : 0;
                    echo \frontend\widgets\item\RelateProduct::widget(['product' => $product,'portal' => $item->type]);
                     }
            }?>
        </div>
    </div>