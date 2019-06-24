<?php

use common\helpers\WeshopHelper;
use common\products\BaseProduct;

/* @var yii\web\View $this */
/* @var BaseProduct $item */
/* @var common\components\StoreManager $storeManager */

$portal_web = strtolower($item->type) == 'ebay' ? '<a href="//ebay.com" rel="nofollow" target="_blank">eBay.com</a>' : '<a href="//amazon.com"  rel="nofollow" target="_blank" >Amazon.com</a>';
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
$instockQuanty = 0;
if ($item->available_quantity) {
    $instockQuanty = $item->quantity_sold ? $item->available_quantity - $item->quantity_sold : $item->available_quantity;
}
$css = <<<CSS
    .wrapper{
    background: #fff;
    }
CSS;
$this->registerCss($css);
?>
<div class="product-full-info">
    <div id="checkcate" style="display: none"><?= $item->category_id ?></div>
    <div class="title">
        <span class="badge-buy-detail"><?= Yii::t('frontend','Buy from the US') ?></span>
        <strong class="name-product"><?= $item->item_name ?></strong>
        <span id="sale-tag" class="sale-tag"
              style="display: <?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
    </div>
    <div class="rating">
        <div class="rate text-orange">
            <i class="la la-star"></i>
            <i class="la la-star"></i>
            <i class="la la-star"></i>
            <i class="la la-star-half-o"></i>
                <i class="la la-star-o"></i>
        </div>
        <span><?php echo Yii::t('frontend', '{star} ({count} customer reviews) on {portal}', ['star' => '4.5/5','count' => rand(10, 100),'portal' => $portal_web]); ?></span>
    </div>
    <div class="condition-and-seller">
        <strong><?= Yii::t('frontend',$current_provider && $current_provider->condition ? $current_provider->condition : $item->condition) ?></strong>
        <span>
            <?php
            if(is_array($item->providers) && count($item->providers)){
              echo Yii::t('frontend','of seller <a href="#" id="seller_name">{seller}</a>',['seller' => $current_provider ? $current_provider->name : '---']);
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
            <?php if ($item->start_price) { ?>
                <b class="old-price"><?= $storeManager->showMoney($item->getLocalizeTotalStartPrice()) ?></b>
                <!--<span class="save"> <?php
                /* Yii::t('frontend', 'Save off: {percent}', [
                        'percent' => $storeManager->showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice())
                    ]); */
                ?>
                </span>-->
            <?php } // Start start_price ?>
        </div>
        <div class="description-shipping-detail">
            <table class="table table-responsive-sm table-none-border w-auto">
                <tr>
                    <td><?= Yii::t('frontend','The above price is included') ?></td>
                    <td>
                        <ul class="list-dot">
                            <li><?= Yii::t('frontend','Purchase fee: <span id="purchase_fee">{purchasefee}</span>',['purchasefee' => $storeManager->showMoney($item->getAdditionalFees()->getTotalAdditionalFees('purchase_fee')[1])]) ?></li>
                            <li><?= Yii::t('frontend','Price product on {portal_web}: <span id="price_origin">{price}</span>',['portal_web'=>$portal_web,'price' => '$'.$item->getSellPrice()]) ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
<!--                    <td colspan="2">--><?//= Yii::t('frontend','The price does not include shipping fees to your location.') ?><!--</td>-->
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
            <label class="label-option-box" id="label_<?= $variationOption->id ?>"><?= $variationOption->name; ?>: ---</label>
            <div class="color-pick" id="<?= $variationOption->id ?>" data-ref="<?= ($variationOption->id) ?>">
                <ul class="style-list"
                    data-slick='{"loop": false; "slidesToShow": <?= count($variationOption->values) < 6 ? count($variationOption->values) : 6 ?>}'>
                    <?php foreach ($variationOption->values as $k => $value) {
                        foreach ($variationOption->images_mapping as $image) {
                            if (strtolower($image->value) == strtolower($value)) {
                                ?>
                                <li class="item">
                                                <span type="spanList" class="box-select-item" tabindex="<?= $k ?>">
                                                    <img src="<?= $image->images ? $image->images[0]->thumb : '/img/no_image.png' ?>"
                                                         data-src="<?= $image->images ? $image->images[0]->main : '/img/no_image.png' ?>"
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
            <label><?= Yii::t('frontend',$variationOption->name) ?>:</label>
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
    if ($item->end_time !== null && $item->type === BaseProduct::TYPE_EBAY) {
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
    if (isset($item->bid) && isset($item->bid['bid_minimum']) && ($item->bid['bid_minimum'])) { ?>
        <div class="" id="" style="display: block; font-size: 12px;color: red">
            <i class="fa fa-exclamation-triangle"></i><b><?= Yii::t('frontend', 'Sorry, the auction system on WESHOP is currently under maintenance. <br> Hope you sympathize!'); ?></b>
        </div>
    <?php }else{
    if (strtolower($item->type) == 'ebay') { ?>
        <div class="qty form-inline" id="" style="display: block; font-size: 12px">
            <b id="instockQuantity"><?= $instockQuanty ?></b><i> <?= Yii::t('frontend', 'products can be purchased'); ?></i>
        </div>
    <?php } ?>
        <div class="qty form-inline" id="quantityGroup" style="display: <?= $current_provider ? 'inline-flex' : 'none' ?>;">
            <label><?= Yii::t('frontend','Quantity') ?></label>
            <button class="btn btnQuantity ml-1" type="button" data-href="down"><i class="la la-minus"></i></button>
            <input type="text" class="form-control" id="quantity" value="1"/>
            <button class="btn btnQuantity" type="button" data-href="up"><i class="la la-plus"></i></button>
        </div>
    <div class="" id="outOfStock" style="display: <?= !$current_provider ? 'block' : 'none' ?>;">
        <label style="color: red"><?= Yii::t('frontend', 'Out of stock') ?></label>
    </div>
        <div class="register-prime">
            <?= Yii::t('frontend','Registering Prime service saves up to 20% of shipping <a href="#"> here </a>') ?>
        </div>
        <?php if($item->end_time !== null && $item->type === BaseProduct::TYPE_EBAY && $item->end_time < time()) {?>
            <div class="" id="outOfStock">
                <h3 style="color: red"><?= Yii::t('frontend', 'Out of Time') ?></h3>
            </div>
        <?php }else if ($item->getLocalizeTotalPrice() > 0 && $current_provider){?>
            <div class="btn-group-detail">
                <div class="btn-group-primary">
                    <button class="btn btn-amazon text-uppercase" id="buyNowBtn"><i class="la la-shopping-cart"></i> <?= Yii::t('frontend','Buy now') ?></button>
                </div>
                <div class="btn-group-secondary">
                    <button class="btn btn-danger text-uppercase" id="installmentBtn"><i class="la la-credit-card"></i> <?= Yii::t('frontend','Installment') ?></button>
                    <button class="btn btn-outline-info text-uppercase" id="addToCart"><i class="la la-cart-plus"></i> <?= Yii::t('frontend','Cart') ?></button>
                </div>
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
                <?= Yii::t('frontend','Weshop disclaims responsibility') ?>
                <?= Yii::t('frontend','All products available for shopping agency services displayed on Weshop are products taken from third party e-commerce websites and are not sold directly by Weshop. Weshop is not responsible if the product is not the same. Therefore, in the event of any violation related to the above products, all debts incurred will be borne by the respective seller on the platform. third party while Weshop will not accept any related, collateral or related responsibilities.') ?>

            </div>
        </div>
    <?php } ?>
</div>
