<?php
/**
 * @var $item \common\products\BaseProduct
 * @var $product \common\products\RelateProduct
 */

use common\helpers\WeshopHelper;
if($item->relate_products) {
    ?>
    <div class="product-viewed product-list box-shadow">
        <div class="title">Sản phẩm liên quan:</div>
        <div id="product-viewed-2" class="owl-carousel owl-theme">
            <?php foreach ($item->relate_products as $product){
                $percent = $product->retail_price && $product->sell_price ? round((($product->retail_price - $product->sell_price) / $product->retail_price)*100,0) : 0;
                ?>
                <div class="item-box">
                    <a href="<?= WeshopHelper::generateUrlDetail($item->type,$product->title,$product->item_id) ?>" class="item">
                        <div class="thumb">
                            <img src="<?= $product->image ?>" alt=""
                                 title=""/>
                        </div>
                        <div class="info">
                            <div class="name" style="white-space: nowrap"><?= $product->title ?></div>
                            <div class="price">
                                <strong><?php
                                    if($product->sell_price){
                                        echo WeshopHelper::showMoney($product->sell_price*Yii::$app->storeManager->getExchangeRate(),1);
                                    }else{
                                        echo "Nhấp vào để xem chi tiết";
                                    }
                                    ?></strong>
                                <?php if($product->retail_price && $product->sell_price < $product->retail_price) {?>
                                    <span><?= WeshopHelper::showMoney($product->retail_price*Yii::$app->storeManager->getExchangeRate(),1); ?></span>
                                    <?php
                                    if ($percent>0){
                                        echo '<span class="sale-tag">'.$percent.'% OFF</span>';
                                    }
                                }?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}
?>