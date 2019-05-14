<?php

use common\helpers\WeshopHelper;
use common\products\BaseProduct;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * @var $item BaseProduct
 */

?>

<div class="product-full-info">
    <a href="#" class="brand"><?= $item->providers ? $item->providers[0]->name : '---' ?></a>
    <div class="title">
        <h2><?= $item->item_name ?></h2>
        <?php
        if (($salePercent = $item->getSalePercent()) > 0) {
            echo ' <span class="sale-tag">' . $salePercent . '% OFF</span>';
        }
        ?>
    </div>
    <div class="rating">
        <div class="rate text-orange">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>
        </div>
        <span>87 người đánh giá</span>
    </div>
    <div class="price">
        <strong class="text-orange"><?=  WeshopHelper::showMoney($item->getLocalizeTotalPrice(),1,'') ?><span class="currency">đ</span></strong>
        <?php if ($item->start_price){ ?>
            <b class="old-price"><?=  WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(),1,'') ?><span class="currency">đ</span></b>
            <span class="save">(Tiết kiệm: <?=  WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(),1,'') ?>đ)</span>
        <?php } ?>
    </div>
    <div class="total-price">(Giá trọn gói về Việt Nam với trọng lượng ước tính <span><?= $item->getShippingWeight() ?> gram</span>)</div>
    <div class="option-box form-inline">
        <label>Tình trạng: <?= $item->condition ?></label>
    </div>
    <?php
    if($item->variation_options){
        $countVariation = count($item->variation_options);
        $checkBoxImg = false;
        foreach ($item->variation_options as $index => $variationOption) {
            /* @var $variationOption \common\products\VariationOption */
            if($variationOption->images_mapping && !$checkBoxImg){ $checkBoxImg = true;?>
                    <div class="option-box">
                        <label><?= $variationOption->name ?>: <?= $variationOption->values ? $variationOption->values[0] : '---' ?></label>
                        <ul class="style-list">
                            <?php foreach ($variationOption->images_mapping as $k => $value) {?>
                                <li type="spanList" class="<?= $k == 0 ? 'active' : '' ?>"><span><img src="<?= $value->images ? $value->images[0]->thumb : '/img/no_image.png' ?>" alt="<?= $value->value ?>" title="<?= $value->value ?>"/></span></li>
                            <?php }?>
                        </ul>
                    </div>
           <?php }else{?>
            <div class="option-box form-inline">
                <label><?= $variationOption->name ?>:</label>
                <select class="form-control form-control-sm" type="dropDown" id="<?= ($variationOption->id) ?>" name="<?= ($variationOption->id) ?>" data-ref="<?= ($variationOption->id) ?>">
                    <?php foreach ($variationOption->values as $k => $v) {?>
                    <option value="<?= $k ?>"><?= $v ?></option>
                <?php }?>
                </select>
            </div>
            <?php }
        }
    }
    ?>
    <ul class="info-list">
        <li>Imported</li>
        <li>Due to a recent redesign by Bulova, recently manufactured Bulova watches,including all watches sold and shipped by Amazon, will not feature the Bulova tuning fork logo on the watch face.</li>
        <li>Brown patterned and rose gold dial</li>
        <li>Leather strap,</li>
        <li>Slightly domed mineral crystal</li>
        <li>Water resistant to 99 feet (30 M): withstands rain and splashes of water, but not showering or submersion</li>
        <li>Case Diameter: 42 mm ; Case Thickness: 11.2 mm ; 3-year Limited Warranty</li>
    </ul>
    <a href="#" class="more text-blue">Xem thêm <i class="fas fa-caret-down"></i></a>
</div>
