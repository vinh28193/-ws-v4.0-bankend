<?php
/**
 * @var $portal string
 * @var $id_suggest string
 * @var $item_suggest array
 * @var common\components\StoreManager $storeManager
 */

use common\components\StoreManager;
use common\helpers\WeshopHelper;
if(!$item_suggest){
    return '';
}
?>
<div class="product-viewed product-list box-shadow">
    <div class="title"><?= $id_suggest == 'suggest_purchase_product' ?
            Yii::t('frontend','Customers who purchased this item also purchased') :
            Yii::t('frontend','Customers who viewed this item also viewed') ?>:</div>
    <!--        <div id="other-seller" class="owl-carousel owl-theme">-->
    <div id="<?= $id_suggest ?>" class="owl-carousel owl-theme">
        <?php foreach ($item_suggest as $item){
                ?>
                <div class="item-box">
                    <a href="<?= WeshopHelper::generateUrlDetail($item['asin_id'],$item['title'],$item['asin_id']) ?>" class="item">
                        <div class="thumb">
                            <img src="<?= $item['images'] && count($item['images']) > 0 ? $item['images'][0] : '/img/no_image.png' ?>" alt=""
                                 title=""/>
                        </div>
                        <div class="info">
                            <div class="name" style="white-space: nowrap"><?= $item['title'] ?></div>
                            <div class="price">
                                <strong><?php
                                    if($item['sell_price']){
                                        if(is_string($item['sell_price'])){
                                            echo $storeManager->showMoney($item['sell_price'] * $storeManager->getExchangeRate());
                                        }else{
                                            foreach ($item['sell_price'] as $k => $sellPrice){
                                                if($k == 0){
                                                    echo $storeManager->showMoney($sellPrice * $storeManager->getExchangeRate());
                                                }elseif($k == count($item['sell_price'])){
                                                    echo ' - '. $storeManager->showMoney($sellPrice * $storeManager->getExchangeRate());
                                                }
                                            }
                                        }
                                    }else{
                                        echo "Nhấp vào để xem chi tiết";
                                    }
                                    ?></strong>
                                <?php if(isset($item['retail_price']) && isset($item['sell_price']) && $item['sell_price'] < $item['retail_price']) {?>
                                    <span><?= $storeManager->showMoney($item['retail_price'] * $storeManager->getExchangeRate()) ?></span>
                                    <?php
                                    $percent = round((($item['retail_price'] - $item['sell_price']) / $item['retail_price'])*100,0);
                                    if ($percent>0){
                                        echo '<span class="sale-tag">'.$percent.'% OFF</span>';
                                    }
                                }?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php
        }?>
    </div>
</div>