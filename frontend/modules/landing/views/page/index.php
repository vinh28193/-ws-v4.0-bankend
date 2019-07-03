<?php

use landing\widgets\LandingJunFiveImgTwoRowOneBig\LandingJunFiveImgTwoRowOneBigWidget;
use landing\widgets\LandingJunFiveImgTwoRowOneBanner\LandingJunFiveImgTwoRowOneBannerWidget;
use landing\widgets\LandingJunFourImgOneRow\LandingJunFourImgOneRowWidget;
use landing\widgets\LandingJunFourImgTowRow\LandingJunFourImgTowRowWidget;
use landing\widgets\LandingJunSevenImgTowRow\LandingJunSevenImgTowRowWidget;
use landing\widgets\LandingJunSixImgOneRow\LandingJunSixImgOneRowWidget;
use landing\widgets\LandingJunThreeImgOneRow\LandingJunThreeImgOneRowWidget;
use landing\widgets\LandingSlider\LandingSliderWidget;
use landing\widgets\LandingUkRequestCalculate\LandingUkRequestCalculateWidget;
use landing\widgets\ListProduct\ListProductWidget;
use landing\widgets\LandingImg\LandingImgWidget;
use landing\widgets\LandingImgFlush\LandingImgFlushWidget;
use landing\widgets\BrandSlider\BrandSliderWidget;
use landing\widgets\LandingBrandSlider\LandingBrandSliderWidget;
use common\models\cms\WsPageItem;
use landing\widgets\ListImage\ListImageWidget;
use landing\widgets\LandingImgList\LandingImgListWidget;
use landing\widgets\LandingSliderFluid\LandingSliderFluidWidget;
use landing\widgets\LandingCrouselProduct\LandingProductCarouselWidget;
use landing\widgets\LandingCrouselProductThumbNail\LandingProductCarouselThumbNailWidget;
use landing\widgets\LandingProductBannerCenter\LandingProductBannerCenterWidget;
use frontend\widgets\cms\FiveImgWidget;
use frontend\widgets\cms\SevenImgWidget;
use frontend\widgets\cms\WeshopBlockWidget;
?>


<main class="lmtk-main">
    <?php
    if (!empty($data_block)) {
        foreach ($data_block as $key => $block) {
            if ($block['block']['type'] == WeshopBlockWidget::BLOCK_CONTENT) {
                ?>
                <div class="container">
                    <?= $block['block']['content'] ?>
                </div>
                <?php
            }
            if ($block['block']['type'] == WeshopBlockWidget::LANDING_SLIDE_FLUID) {
                ?>
                <?= LandingSliderFluidWidget::widget(['block' => $block]) ?>
            <?php }


            if ($block['block']['type'] == WeshopBlockWidget::LANDING_SLIDE) { ?>
                <?= LandingSliderWidget::widget(['block' => $block]) ?>
            <?php }
            if ($block['block']['type'] == "LANDING") { ?>
                <?= ListProductWidget::widget(['block' => $block]) ?>
            <?php } ?>

            <?php if ($block['block']['type'] == 'LANDING_IMG') {
                echo LandingImgWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_IMG_FLUID) {
                echo LandingImgFlushWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::BRAND_SLIDER) {
                echo BrandSliderWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_BRAND_SLIDER) {
                echo LandingBrandSliderWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_IMG_GIRD) {
                echo ListImageWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_IMG_LIST) {
                echo LandingImgListWidget::widget(['block' => $block]);
            } ?>


            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_PRODUCT_CAROUSEL) {
                echo LandingProductCarouselWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_PRODUCT_CAROUSEL_THUMB_NAILS) {
                //echo LandingProductCarouselThumbNailWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::LANDING_PRODUCT_BANNER_CENTER) {
                echo LandingProductBannerCenterWidget::widget(['block' => $block]);
            } ?>

            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_4_IMG_2_ROW) {
                echo LandingJunFourImgTowRowWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_3_IMG_1_ROW) {
                echo LandingJunThreeImgOneRowWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_4_IMG_1_ROW) {
                echo LandingJunFourImgOneRowWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_6_IMG_1_ROW) {
                echo LandingJunSixImgOneRowWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_5_IMG_2_ROW_1_BIG) {
                echo LandingJunFiveImgTwoRowOneBigWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_5_IMG_2_ROW_1_BANNER) {
                echo LandingJunFiveImgTwoRowOneBannerWidget::widget(['block' => $block]);
            }
            ?>
            <?php
            if ($block['block']['type'] == WeshopBlockWidget::JUN_LANDING_7_IMG_2_ROW) {
                echo LandingJunSevenImgTowRowWidget::widget(['block' => $block]);
            }
            ?>

            <?php
            if ($block['block']['type'] == WeshopBlockWidget::UK_REQUEST_CALCULATE) {
                echo LandingUkRequestCalculateWidget::widget(['block' => $block]);
            }
            ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::BLOCK7) {
                echo SevenImgWidget::widget(['block' => $block]);
            } ?>

            <?php if ($block['block']['type'] == WeshopBlockWidget::BLOCK5) { ?>
                <div class="product-block amazon block-column">
                    <div class="container">
                        <div class="row">
                            <?php foreach ($data_block as $key1 => $value1) {
                                if ($value1['block']['type'] == WeshopBlockWidget::BLOCK5) {
                                    echo FiveImgWidget::widget(['block' => $value1]);
                                    unset($data_block[$key1]);
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <?php }
    } ?>
</main>
