<?php

use common\helpers\WeshopHelper;
use common\products\BaseProduct;

/**
 * @var $item BaseProduct
 */
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
        <span><?php echo(rand(10, 100)); ?> người đánh giá</span>
    </div>
    <div class="origin" style="display: none">
        <a target="_blank" href="<?= $item->item_origin_url ?>">Xem link gốc -></a>
    </div>

    <?php if ($item->getLocalizeTotalPrice() > 0) { ?>
        <div class="price">
            <strong class="text-orange one-time-payment"><?= WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, '') ?>
                <span class="currency">đ</span></strong>
            <?php if ($item->start_price) { ?>
                <b class="old-price"><?= WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') ?><span
                            class="currency">đ</span></b>
                <span class="save">(Tiết kiệm: <?= WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') ?>đ)</span>
            <?php } // Start start_price ?>
        </div> <!-- class="price" -->
        <div class="total-price">(Giá trọn gói về Việt Nam với trọng lượng ước tính
            <span><?= $item->getShippingWeight() ?> kg</span>)
        </div>
        <div class="option-box form-inline">
            <label>Tình trạng: <?= $item->condition ? $item->condition : 'Không xác định' ?></label>
        </div>
        <?php
    if ($item->variation_options) {
        $countVariation = count($item->variation_options);
    foreach ($item->variation_options as $index => $variationOption) {
        /* @var $variationOption \common\products\VariationOption */
    if ($variationOption->images_mapping && $variationUseImage === $index) {
        ?>
        <div class="option-box">
            <label id="label_<?= $variationOption->id ?>"><?= $variationOption->name; ?>: ---</label>
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
                                                <span type="spanList" tabindex="<?= $k ?>">
                                                    <img src="<?= $image->images ? $image->images[0]->thumb : '/img/no_image.png' ?>"
                                                         alt="<?= $value ?>" title="<?= $value ?>"/>
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
        echo '<div class="countdown">Thời gian còn lại: <b class="text-orange">' . Yii::$app->formatter->asDatetime($item->end_time) . '</b> (<span data-toggle="countdown-time" data-timestamp="' . $item->end_time . '" data-prefix="Còn" data-day="ngày" data-hour="giờ" data-minute="phút" data-second="giây"></span>)</div>';
    }
    ?>
    <?php }else {  //ToDo 0 dong  ?>
        <script>
            $(document).ready(function () {
                $("#outOfStock").css('display', 'block');
                $("#quantityGroup").css('display', 'none');
                $("#quoteBtn").css('display', 'block');
                $("#buyNowBtn").css('display', 'none');
            });
        </script>
    <?php } //esle 0 Dong ?>
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
