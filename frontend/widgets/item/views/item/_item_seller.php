<?php
/**
 * @var $item \common\products\BaseProduct
 * @var common\components\StoreManager $storeManager
 * @var \common\products\Provider $provider
 */

use common\components\StoreManager;
use common\helpers\WeshopHelper;
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
            <div class="name" style="white-space: nowrap"><b><?= Yii::t('frontend','Condition: ') ?></b><?= $provider->condition ?></div>
            <div class="price">
                <strong><?php
                    if($item->sell_price){
                        echo $storeManager->showMoney($item->getLocalizeTotalPrice());
                    }else{
                        echo "Nhấp vào để xem chi tiết";
                    }
                    ?></strong>
                <?php if($item->retail_price && $item->sell_price < $item->retail_price) {?>
                    <span><?= $storeManager->showMoney($item->getLocalizeTotalStartPrice()); ?></span>
                    <?php
                    $percent = round((($item->retail_price - $item->sell_price) / $item->retail_price)*100,0);
                    if ($percent>0){
                        echo '<span class="sale-tag">'.$percent.'% OFF</span>';
                    }
                }?>
            </div>
        </div>
    </a>
</div>
