<?php

use common\helpers\WeshopHelper;
use common\products\BaseProduct;

/* @var yii\web\View $this */
/* @var BaseProduct $item */
/* @var common\components\StoreManager $storeManager */

$salePercent = $item->getSalePercent();
$current_provider = $item->getCurrentProvider();
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
?>
<div id="checkcate" style="display: none"><?= $item->category_id ?></div>
<div class="product-full-info">
    <a href="#" class="brand"><?= $current_provider ? $current_provider->name : '---' ?></a>
    <div class="title">
        <h2><?= $item->item_name ?></h2>
        <span id="sale-tag" class="sale-tag"
              style="display: <?= $salePercent > 0 ? 'block' : 'none' ?>"><?= $salePercent > 0 ? $salePercent : '' ?>% OFF</span>
    </div>
    <div class="rating">
        <div class="rate text-orange">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>
        </div>
        <span><?php echo Yii::t('frontend', '{count} assessor', ['count' => rand(10, 100)]); ?></span>
    </div>
    <div class="origin" style="display: none">
        <a target="_blank"
           href="<?= $item->item_origin_url ?>"><?= Yii::t('frontend', 'See the original link ->') ?></a>
    </div>

    <?php if ($item->getLocalizeTotalPrice() > 0) { ?>
        <div class="price">
            <strong class="text-orange one-time-payment">
                <?= $storeManager->showMoney($item->getLocalizeTotalPrice()) ?>
            </strong>
            <?php if ($item->start_price) { ?>
                <b class="old-price"><?= $storeManager->showMoney($item->getLocalizeTotalStartPrice()) ?></b>
                <span class="save"> <?= Yii::t('frontend', 'Save off: {percent}', [
                        'percent' => $storeManager->showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice())
                    ]); ?>
                </span>
            <?php } // Start start_price ?>
        </div> <!-- class="price" -->
        <div class="total-price">
            <?= Yii::t('frontend', 'Weight temporary: {temporary} {unit}', [
                'temporary' => $item->getShippingWeight(),
                'unit' => 'kg'
            ])
            ?>
        </div>
        <div class="option-box form-inline">
            <label>
                <?=Yii::t('frontend','Condition : {condition}',[
                    'condition' => $item->condition ? $item->condition : Yii::t('frontend','Unknown')
                ]);?>
            </label>
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
                <i class="fas fa-chevron-left slider-prev2"></i>
                <i class="fas fa-chevron-right slider-next2"></i>
                <ul class="style-list"
                    data-slick='{"slidesToShow": <?= count($variationOption->values) < 6 ? count($variationOption->values) : 6 ?>}'>
                    <?php foreach ($variationOption->values as $k => $value) {
                        foreach ($variationOption->images_mapping as $image) {
                            if (strtolower($image->value) == strtolower($value)) {
                                ?>
                                <li class="item">
                                                <span type="spanList" class="box-select-item" tabindex="<?= $k ?>">
                                                    <img src="<?= $image->images ? $image->images[0]->thumb : '/img/no_image.png' ?>"
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
        <div class="option-box form-inline">
            <label><?= $variationOption->name ?>:</label>
            <select class="form-control form-control-sm" type="dropDown" id="<?= ($variationOption->id) ?>"
                    name="<?= ($variationOption->id) ?>" data-ref="<?= ($variationOption->id) ?>">
                <option value=""></option>
                <?php foreach ($variationOption->values as $k => $v) { ?>
                    <option value="<?= $k ?>"><?= $v ?></option>
                <?php } ?>
            </select>
        </div>
    <?php }
    }

    }
    if ($item->end_time !== null && $item->type === BaseProduct::TYPE_EBAY) {
        ?>

        <div class="countdown"><?=Yii::t('frontend','Time end')?>: <b class="text-orange"><?=Yii::$app->formatter->asDatetime($item->end_time);?></b> (<span data-toggle="countdown-time" data-timestamp="<?=$item->end_time?>" data-prefix="<?=Yii::t('frontend','Also');?>" data-day="<?=Yii::t('frontend','day');?>" data-hour="<?=Yii::t('frontend','hour');?>" data-minute="<?=Yii::t('frontend','minute');?>" data-second="<?=Yii::t('frontend','second');?>"></span>)</div>;
        <?php
        }
    ?>
    <?php }else {  //ToDo 0 dong
    $js = <<<JS
    $(document).ready(function () {
                    $("#outOfStock").css('display', 'block');
                    $("#quantityGroup").css('display', 'none');
                    $("#quoteBtn").css('display', 'block');
                    $("#buyNowBtn").css('display', 'none');
                });
JS;
    $this->registerJs($js);
        } //esle 0 Dong ?>
    <?php
    try {
        if ($item->description) {
            if (is_array($item->description)) { ?>
                <ul class="info-list">
                    <?php foreach ($item->description as $ds) { ?>
                        <li><?= $ds ?></li>
                    <?php } ?>
                </ul>
                <div class="see-more text-blue">
                    <!--            <a class="more" href="#description_extra">Xem thêm <i class="fas fa-caret-down"></i></a>-->
                    <span class="more">Xem thêm <i class="fas fa-caret-down"></i></span>
                    <span class="less">Ẩn bớt <i class="fas fa-caret-up"></i></span>
                </div>
                <?php
            }
        }
    } catch (Exception $exception) {

    } ?>
    <a href="#" class="banner">
        <img src="/img/detail-banner.jpg" alt="">
    </a>
</div>
