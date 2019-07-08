<?php

use common\components\UserCookies;
use common\helpers\WeshopHelper;
use common\products\BaseProduct;
use yii\helpers\ArrayHelper;

/* @var yii\web\View $this */
/* @var BaseProduct $item */
/* @var common\components\StoreManager $storeManager */
$portal_web = strtolower($item->type) == 'ebay' ? '<a href="'.$item->item_origin_url.'" rel="nofollow" target="_blank">eBay.com</a>' : '<a href="'.$item->item_origin_url.'"  rel="nofollow" target="_blank" >Amazon.com</a>';
$salePercent = $item->getSalePercent();
$current_provider = $item->provider ? $item->provider : $item->getCurrentProvider();
$variationUseImage = null;
foreach ($item->variation_options as $index => $variationOption) {
    if ($variationOption->images_mapping) {
        foreach ($variationOption->values as $k => $value) {
            foreach ($variationOption->images_mapping as $image) {
                if (strtolower($image->value) == strtolower($value)) {
                    $variationUseImage = $index;
                    break;
                }
            }
        }
    }
}
$instockQuanty = $item->type == BaseProduct::TYPE_EBAY ? 0 : 50;
if ($item->available_quantity) {
    $instockQuanty = $item->quantity_sold ? $item->available_quantity - $item->quantity_sold : $item->available_quantity;
}
$css = <<<CSS
    .wrapper{
    background: #fff;
    }
CSS;
$this->registerCss($css);
$js = <<<JS
$(document).ready(function() {
    $('.la-question-circle').tooltip({'trigger':'hover'});
});
JS;
$this->registerJs($js);

$rate_star = floatval($item->rate_star);
$rate_count = $item->rate_count ? $item->rate_count : 0;
$rate_star = $rate_star > intval($rate_star) ? intval($rate_star).'-5' : intval($rate_star);
$internal_shipping = $item->getInternationalShipping();
$internal_shipping_fees = $item->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee');
$internal_shipping_fee = $internal_shipping_fees[1] ? $storeManager->showMoney($internal_shipping_fees[1]) : '---';
$userCookies = new UserCookies();
$userCookies->setUser();
?>
<div class="product-full-info">
    <div id="checkcate" style="display: none"><?= $item->category_id ?></div>
    <div class="title">
        <span class="badge-buy-detail"><?= Yii::t('frontend','Buy from the US') ?></span>
        <strong class="name-product"><?= $item->item_name ?></strong>
        <span id="sale-tag" class="sale-tag"
              style="display: <?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
    </div>
    <?php if(strtolower($item->type) != 'ebay' ){?>
        <div class="rating">
            <div class="rate text-orange">
                <i class="a-icon a-icon-star a-star-<?= $rate_star ?> review-rating">
                    <span class="a-icon-alt"><?= str_replace('-','.',$rate_star) ?> out of 5 stars</span>
                </i>
            </div>
            <span><a id="countStarDetail" href="javascript:void(0);"><?php echo Yii::t('frontend', '{star} ({count} customer reviews) on {portal}', ['star' => str_replace('-','.',$rate_star).'/5','count' => $rate_count ? $rate_count : 0,'portal' => $portal_web]); ?></a></span>
        </div>
    <?php } ?>
    <div class="condition-and-seller">
        <strong><?= Yii::t('frontend',$current_provider && $current_provider->condition ? ($current_provider->condition ? $current_provider->condition : 'Use or new') : ($item->condition ? $item->condition : 'Use or new')) ?></strong>
        <span>
            <?php
            if(is_array($item->providers) && count($item->providers)){
                echo Yii::t('frontend','of seller <a href="#" id="seller_name">{seller}</a>',['seller' => $current_provider ? $current_provider->name : '---']);
            }
            if($item->type === BaseProduct::TYPE_EBAY && $current_provider && $current_provider->positive_feedback_percent && $current_provider->rating_score){
                echo "<a href='javascript:void(0)' class=\"text-black\">&nbsp;(".$current_provider->rating_score."&nbsp;<i class='la la-star text-warning'></i>)&nbsp;".Yii::t('frontend','{positive}% positive',['positive' => $current_provider->positive_feedback_percent])."</a>";
            }elseif($item->type === BaseProduct::TYPE_AMAZON_US && $item->is_prime){
                echo "<img style='height: 14px;padding-left: 5px;' src='/images/logo/prime.png'>";
            }
            ?>
        </span>
    </div>

    <div class="origin" style="display: none">
        <a target="_blank"
           href="<?= $item->item_origin_url ?>"><?= Yii::t('frontend', 'See the original link ->') ?></a>
    </div>
    <div class="mb-slide-image">
        <?php
        foreach ($item->primary_images as $image){ ?>
            <img src="<?= $image->main ?>"/>
        <?php }
        ?>
    </div>
    <?php if ($item->getLocalizeTotalPrice() > 0) { ?>
        <div class="price">
            <div class="title-price"><?= Yii::t('frontend','Price') ?></div>
            <strong class="text-danger one-time-payment">
                <?= $storeManager->showMoney($item->getLocalizeTotalPrice()) ?>
            </strong>
            <?php if ($item->start_price && $item->start_price > $item->sell_price) { ?>
                <b class="old-price"><?= $storeManager->showMoney($item->getLocalizeTotalStartPrice()) ?></b>
            <?php } ?>
        </div>
        <div class="description-shipping-detail">
            <table class="table table-responsive-sm table-none-border w-auto">
                <tr>
                    <td><?= Yii::t('frontend','The above price is included') ?></td>
                    <td>
                        <ul class="list-dot">
                            <li><?= Yii::t('frontend','Price product on {portal_web}: <span id="price_origin_local">{price_local}</span> (<span id="price_origin">{price}</span>)',['portal_web'=>$portal_web,'price' => '$'.$item->getSellPrice(),'price_local' => $storeManager->showMoney($item->getAdditionalFees()->getTotalAdditionalFees('product_price')[1])]) ?></li>
                            <li><?= Yii::t('frontend','Purchase fee: <span id="purchase_fee">{purchasefee}</span>',['purchasefee' => $storeManager->showMoney($item->getAdditionalFees()->getTotalAdditionalFees('purchase_fee')[1])]) ?></li>
                        </ul>
                    </td>
                </tr>
                <?php
                if ($item->type === BaseProduct::TYPE_EBAY){
                    ?>
                    <tr>
                        <td><?= Yii::t('frontend','Location Seller') ?></td>
                        <td><?= $current_provider->location.', '.$current_provider->country_code ?></td>
                    </tr>
                <?php  } ?>
                <tr>
                    <td>
                        <?= Yii::t('frontend','Shipping detail') ?><br>
                    </td>
                    <td>
                        <ul class="list-dot">
                            <li><?= Yii::t('frontend','Weight: <span id="shipping_weight">{shipping_weight}kg</span> <i class="la la-question-circle" title="{shipping_weight} is temporary weight."></i>',['shipping_weight' => $item->shipping_weight ]) ?></li>
                            <li><?= Yii::t('frontend','Internal Shipping fee: <span id="shipping_fee">{shipping_fee}</span>',['shipping_fee' => $userCookies->province_id && $userCookies->district_id ? $internal_shipping_fee : '<a href="javascript:void();" onclick="ws.showModal(\'modal-address\')">Click here</a>']) ?></li>
                            <?php if ($userCookies->checkAddress()){ ?>
                                <li>
                                    <?= Yii::t('frontend','Estimate time: <span id="time_estimate_min">{min_time}</span> - <span id="time_estimate_max">{max_time}</span> days',['min_time'=> $userCookies->checkAddress() && isset($internal_shipping[0]) ? ArrayHelper::getValue($internal_shipping[0],'min_delivery_time','') : '','max_time' => $userCookies->checkAddress() && isset($internal_shipping[0]) ? ArrayHelper::getValue($internal_shipping[0],'max_delivery_time','<a href="javascript:void();" onclick="ws.showModal(\'modal-address\')">Click here</a>') : '<a href="javascript:void();" onclick="ws.showModal(\'modal-address\')">Click here</a>' ]) ?><br>
                                    (<?= Yii::t('frontend','Ship to <a onclick="ws.showModal(\'modal-address\')" class="text-blue" style="color: #2e9ab8;cursor: pointer;">{district_name}, {province_name}</a>',$userCookies->checkAddress() ? ['district_name' => $userCookies->getDistrict() ? ($storeManager->isID() ? $userCookies->zipcode.', '.$userCookies->getDistrict()->name : $userCookies->getDistrict()->name ) : '', 'province_name' => $userCookies->getProvince() ? $userCookies->getProvince()->name : ''] : ['district_name' => '', 'province_name' => '<a href="javascript:void();" onclick="ws.showModal(\'modal-address\')">Click here</a>']) ?>)
                                </li>
                           <?php }
                            ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    if ($item->variation_options) {
        $countVariation = count($item->variation_options);
    foreach ($item->variation_options as $index => $variationOption) {
        /* @var $variationOption \common\products\VariationOption */
    if ($variationOption->images_mapping && $variationUseImage === $index) {
        ?>
        <div class="option-box">
            <label class="label-option-box" id="label_<?= $variationOption->id ?>"><?= $variationOption->name; ?>: ---</label><span class="error" id="error-<?= ($variationOption->id) ?>"></span>
            <div class="color-pick" id="<?= $variationOption->id ?>" data-ref="<?= ($variationOption->id) ?>">
                <ul class="style-list">
                    <?php foreach ($variationOption->values as $k => $value) {
                        foreach ($variationOption->images_mapping as $image) {
                            if (strtolower($image->value) == strtolower($value)) {
                                ?>
                                <li class="item">
                                                <span type="spanList" class="box-select-item" tabindex="<?= $k ?>">
                                                    <img src="<?= $image->images ? (is_string($image->images[0]->thumb) ? $image->images[0]->thumb : $image->images[0]->thumb[0] ) : '/img/no_image.png' ?>"
                                                         data-src="<?= $image->images ? (is_string($image->images[0]->main) ? $image->images[0]->main : $image->images[0]->main[0] ) : '/img/no_image.png' ?>"
                                                         alt="<?= $variationOption->name; ?>: <?= $value ?>" title="<?= $value ?>"/>
                                                </span>
                                </li>
                                <?php break;
                            }
                        }
                    } ?>
                </ul>
            </div>
        </div>
    <?php }else {
        ?>
        <div class="option-box form-group">
            <label><?= Yii::t('frontend',$variationOption->name) ?>:</label><span class="error" id="error-<?= ($variationOption->id) ?>"></span>
            <select class="form-control w-auto" type="dropDown" id="<?= ($variationOption->id) ?>"
                    name="<?= ($variationOption->id) ?>" data-ref="<?= ($variationOption->id) ?>">
                <option value=""></option>
                <?php foreach ($variationOption->values as $k => $v) { ?>
                    <option value="<?= $k ?>"><?= Yii::t('frontend',$v) ?></option>
                <?php } ?>
            </select>
        </div>
    <?php }
    }

    }
    if ($item->end_time !== null && $item->type === BaseProduct::TYPE_EBAY && $item->end_time < (time() + 60*60*24*7 )) {
        ?>

        <div class="countdown"><?=Yii::t('frontend','Time end')?>: <b class="text-orange"><?=Yii::$app->formatter->asDatetime($item->end_time);?></b> (<span data-toggle="countdown-time" data-timestamp="<?=$item->end_time?>" data-prefix="<?=Yii::t('frontend','Also');?>" data-day="<?=Yii::t('frontend','day');?>" data-hour="<?=Yii::t('frontend','hour');?>" data-minute="<?=Yii::t('frontend','minute');?>" data-second="<?=Yii::t('frontend','second');?>"></span>)</div>
        <?php
        }
    ?>
    <?php }else {  //ToDo 0 dong
    $js = <<<JS
    $(document).ready(function () {
                    $("#outOfStock").css('display', 'block');
                    $("#quantityGroup").css('display', 'none');
                    $("#btn-group-detail").css('display', 'none');
                    $("#quoteBtn").css('display', 'block');
                    $("#buyNowBtn").css('display', 'none');
                });
JS;
    $this->registerJs($js);
        } //esle 0 Dong ?>
    <?php
    if (isset($item->bid) && isset($item->bid['bid_minimum']) && ($item->bid['bid_minimum']) && !(ArrayHelper::getValue($item->bid,'allow_by_now'))) { ?>
        <div class="" id="" style="display: block; font-size: 12px;color: red">
            <i class="fa fa-exclamation-triangle"></i><b><?= Yii::t('frontend', 'Sorry, the auction system on WESHOP is currently under maintenance. <br> Hope you sympathize!'); ?></b>
        </div>
    <?php }else{?>
        <div class="qty form-inline" id="quantityGroup" style="display: <?= $current_provider ? 'inline-flex' : 'none' ?>;">
            <label><?= Yii::t('frontend','Quantity') ?></label>
            <button class="btn btnQuantity ml-1" type="button" data-href="down"><i class="la la-minus"></i></button>
            <input type="text" class="form-control" id="quantity" value="1"/>
            <button class="btn btnQuantity" type="button" data-href="up"><i class="la la-plus"></i></button>
            <?php
            if (strtolower($item->type) == 'ebay') { ?>
                <i><?= Yii::t('frontend', 'More than {quantityStock} available',['quantityStock' => $instockQuanty]); ?></i>
                <i class="a-icon a-icon-text-separator" role="img" aria-label="|"></i>
                <i class="text-danger"><?= Yii::t('frontend', '{quantitySold} sold',['quantitySold' => $item->quantity_sold]); ?></i>
            <?php }
            ?>
        </div>
    <div class="" id="outOfStock" style="display: <?= !$current_provider ? 'block' : 'none' ?>;">
        <label style="color: red"><?= Yii::t('frontend', 'Out of stock') ?></label>
    </div>
        <div class="register-prime">
            <?= Yii::t('frontend','Registering Prime service saves up to 20% of shipping <a href="#"> here </a>') ?>
        </div>
        <?php if(($item->end_time !== null && $item->type === BaseProduct::TYPE_EBAY && $item->end_time < time())) {?>
            <div class="" id="outOfStock">
                <h3 style="color: red"><?= Yii::t('frontend', 'Out of Time') ?></h3>
            </div>
        <?php }else if ($item->getLocalizeTotalPrice() > 0 && $current_provider && $instockQuanty > 0){?>
            <div class="btn-group-detail">
                <?php if ($item->checkInstallment() && false){?>
                    <div class="btn-group-primary w-50">
                        <button class="btn btn-amazon text-uppercase" id="buyNowBtn"><i class="la la-shopping-cart"></i> <?= Yii::t('frontend','Buy now') ?></button>
                    </div>
                    <div class="btn-group-secondary w-50">
                        <button class="btn btn-danger text-uppercase" id="installmentBtn"><i class="la la-credit-card"></i> <?= Yii::t('frontend','Installment') ?></button>
                        <button class="btn btn-outline-info text-uppercase" id="addToCart"><i class="la la-cart-plus"></i> <?= Yii::t('frontend','Cart') ?></button>
                    </div>
                <?php }else{ ?>
                    <div class="btn-group-secondary">
                        <button class="btn btn-amazon text-uppercase" id="buyNowBtn" style="width: 50%; display: block; float: left;margin-right: 5px;"><i class="la la-shopping-cart"></i> <?= Yii::t('frontend','Buy now') ?></button>
                        <button class="btn btn-outline-info text-uppercase" id="addToCart" style="width: auto; display: block; margin-left: 5px;"><i class="la la-cart-plus"></i> <?= Yii::t('frontend','Cart') ?></button>
                    </div>
                <?php } ?>
            </div>
        <?php }else{?>
            <div class="" id="outOfStock">
                <h3 style="color: red"><?= Yii::t('frontend', 'Out of stock') ?></h3>
            </div>
        <?php }?>
        <div class="card-group-detail">
            <span><img src="/img/bank/icon-visa.jpg"></span>
            <span><img src="/img/bank/icon-master.jpg"></span>
            <span><img src="/img/bank/icon-napas.jpg"></span>
            <hr>
        </div>
        <div class="rules-weshop">
            <div class="header-rule"><i class="la la-bullhorn"></i><?= Yii::t('frontend','Weshop disclaims responsibility') ?></div>
            <div class="content-rules">
                <?php //  Yii::t('frontend','Weshop disclaims responsibility') ?>
                <?= Yii::t('frontend','All products available for shopping agency services displayed on Weshop are products taken from third party e-commerce websites and are not sold directly by Weshop. Weshop is not responsible if the product is not the same. Therefore, in the event of any violation related to the above products, all debts incurred will be borne by the respective seller on the platform. third party while Weshop will not accept any related, collateral or related responsibilities.') ?>

            </div>
        </div>
    <?php }
    if($item->type == BaseProduct::TYPE_AMAZON_US && count($item->providers) > 1){
        ?>
        <div class="seller-block">
            <div class="title">
                <?= Yii::t('frontend','Other sellers') ?>:
            </div>
            <div>
                <table class="table table-responsive-md">
                    <thead>
                    <tr>
                        <th><?= Yii::t('frontend','Price + Shipping') ?></th>
                        <th><?= Yii::t('frontend','Condition') ?></th>
                        <th><?= Yii::t('frontend','Seller info') ?></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach ($item->providers as $provider) {
                        if($provider->prov_id != $current_provider->prov_id){
                            $item->updateBySeller($provider->prov_id);
                            $rate_star_seller = floatval($provider->rating_star);
                            $rate_count_seller = $provider->rating_score ? $provider->rating_score : 0;
                            $rate_star_seller = $rate_star_seller > intval($rate_star_seller) ? intval($rate_star_seller).'-5' : intval($rate_star_seller);
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-danger">
                                        <?= $storeManager->showMoney($item->getLocalizeTotalPrice()); ?>
                                    </strong>
                                    <?php if ($item->start_price && $item->start_price > $item->sell_price) { ?>
                                        <br>
                                        <b class="old-price"><?= $storeManager->showMoney($item->getLocalizeTotalStartPrice()) ?></b>
                                    <?php } ?>
                                </td>
                                <td>
                                    <strong><?= $provider->condition; ?></strong>
                                </td>
                                <td>
                                    <div class="text-blue"><?= $provider->name; ?></div>
                                        <div class="rate text-orange">
                                            <?php if ($rate_star_seller) { ?>
                                                <i class="a-icon a-icon-star a-star-<?= $rate_star_seller ?> review-rating">
                                                    <span class="a-icon-alt"><?= str_replace('-','.',$rate_star_seller) ?> out of 5 stars</span>
                                                </i>
                                            <?php }if ($provider->positive_feedback_percent) { ?>
                                                <u class="text-blue font-weight-bold"><?= Yii::t('frontend','{positive}% positive',['positive' => $provider->positive_feedback_percent]) ?></u>
                                            <?php }
                                            if ($rate_count_seller) {
                                                ?>
                                                <br>
                                                <u class="text-blue font-weight-bold">(<?= Yii::t('frontend','{countRate} total rating',['countRate' => $rate_count_seller]) ?>)</u>
                                            <?php } ?>
                                        </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"  class="btn btn-amazon shortcut-payment"  data-role="buynow" data-seller="<?=$provider->prov_id;?>" style="border-radius: 0px"><?= Yii::t('frontend','Buy now') ?></a>
                                    <a href="javascript:void(0)"  class="btn btn-outline-info shortcut-payment" data-role="shopping" data-seller="<?=$provider->prov_id;?>" style="border-radius: 0px"><?= Yii::t('frontend','Cart') ?></a>
                                </td>
                            </tr>
                        <?php }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
