<?php
/**
 * @var $item \common\products\BaseProduct
 * @var common\components\StoreManager $storeManager
 */

use common\components\StoreManager;use common\helpers\WeshopHelper;

$sellerCurrent = Yii::$app->request->get('seller');
$sellerCurrent = $sellerCurrent ? $sellerCurrent : $item->getSeller();
 ?>
    <div class="product-viewed product-list box-shadow" id="other-seller-div" style="display: <?= ($item->providers && count($item->providers) > 1) ? 'block' : 'none' ?>">
        <div class="title"><?= Yii::t('frontend','Other sellers') ?>:</div>
        <div class="owl-carousel owl-theme" id="other-seller">
            <?php
            if($item->providers && count($item->providers) > 1){
                foreach ($item->providers as $provider){
                    if($provider->prov_id != $sellerCurrent){
                        $item->updateBySeller($provider->prov_id);
                        echo \frontend\widgets\item\SellerMoreWidget::widget(['provider' => $provider,'item' => $item ,'storeManager' => $storeManager]);
                    }
                }
            } ?>
        </div>
    </div>