<?php
/**
 * @var $item \common\products\BaseProduct
 */

use common\helpers\WeshopHelper;

$sellerCurrent = Yii::$app->request->get('seller');
$sellerCurrent = $sellerCurrent ? $sellerCurrent : $item->getSeller();
 ?>
    <div class="product-viewed product-list">
        <div class="title"><?= Yii::t('frontend','Other sellers') ?>:</div>
<!--        <div id="other-seller" class="owl-carousel owl-theme">-->
        <div id="other-seller" class="owl-carousel owl-theme">
            <?php foreach ($item->providers as $provider){
                if($provider->prov_id != $sellerCurrent){
                    $item->updateBySeller($provider->prov_id);
                $percent = $item->retail_price && $item->sell_price ? round(($item->retail_price - $item->sell_price) / $item->retail_price,0) : 0;
                ?>
                <div class="item-box">
                    <a href="<?= WeshopHelper::generateUrlDetail($item->type,$item->item_name,$item->item_id,null,$provider->prov_id) ?>" class="item">
                        <div class="thumb">
                            <img src="<?= $item->primary_images && count($item->primary_images) > 0 ? $item->primary_images[0]->main : '/img/no_image.png' ?>" alt=""
                                 title=""/>
                        </div>
                        <div class="info">
                            <div class="name" style="white-space: nowrap"><?= $item->item_name ?></div>
                            <div class="name" style="white-space: nowrap"><b><?= Yii::t('frontend','Solid by: ') ?></b><?= $provider->name ?></div>
                            <div class="price">
                                <strong><?php
                                    if($item->sell_price){
                                        echo WeshopHelper::showMoney($item->sell_price*Yii::$app->storeManager->getExchangeRate(),1);
                                    }else{
                                        echo "Nhấp vào để xem chi tiết";
                                    }
                                    ?></strong>
                                <?php if($item->retail_price && $item->sell_price < $item->retail_price) {?>
                                    <span><?= WeshopHelper::showMoney($item->retail_price*Yii::$app->storeManager->getExchangeRate(),1); ?></span>
                                    <?php
                                    if ($percent>0){
                                        echo '<span class="sale-tag">'.$percent.'% OFF</span>';
                                    }
                                }?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }
            }?>
        </div>
    </div>