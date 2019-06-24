<?php
/**
 * @var $array array
 * @var $portal string
 * @var $storeManager \common\components\StoreManager
 */
?>
<div class="detail-block-2 box-shadow" id="feed_back">
    <div class="row">
        <div class="col-md-12">
            <div class="title"><?=Yii::t('frontend','Customer Reviews On Amazon'); ?>:</div>
        </div>
        <div class="col-md-12">
            <?php foreach ($array as $value){
                $starStr = \yii\helpers\ArrayHelper::getValue($value,'review_start','');
                $starArr = explode(' ',\yii\helpers\ArrayHelper::getValue($value,'review_start',''));
                $star = $starArr && count($starArr) > 0 && intval($starArr[0]) > 1 ? intval($starArr[0]) : 1;
                ?>
                <div class="a-section celwidget">
                    <div class="a-row a-spacing-mini">
                        <a class="a-profile" data-a-size="small">
                            <div aria-hidden="true" class="a-profile-avatar-wrapper">
                                <div class="a-profile-avatar">
                                    <img src="<?= \yii\helpers\ArrayHelper::getValue($value,'avatar','/img/no_image.png') ?>" class="">
                                </div>
                            </div>
                            <div class="a-profile-content"><span class="a-profile-name"><?= \yii\helpers\ArrayHelper::getValue($value,'name','Unknown') ?></span></div>
                        </a>
                    </div>
                    <div class="a-row">
                        <a class="a-link-normal" title="<?= \yii\helpers\ArrayHelper::getValue($value,'review_start','') ?>"
                           href="javascript:void (0)">
                            <i class="a-icon a-icon-star a-star-<?= $star ?> review-rating">
                                <span class="a-icon-alt"><?= \yii\helpers\ArrayHelper::getValue($value,'review_start','') ?></span>
                            </i>
                        </a>
                        <span class="a-letter-space"></span>
                        <a class="a-size-base a-link-normal review-title a-color-base review-title-content a-text-bold"
                           href="javascript:void (0)">
                            <span class=""><?= \yii\helpers\ArrayHelper::getValue($value,'review_title','') ?></span>
                        </a>
                        <span class="a-letter-space"></span>
                    </div>
                    <span class="a-size-base a-color-secondary review-date"><?= \yii\helpers\ArrayHelper::getValue($value,'day_review','In month') ?></span>
                    <div class="a-row a-spacing-mini review-data review-format-strip">
            <span class="a-color-secondary">
                <?= \yii\helpers\ArrayHelper::getValue($value,'format_strip-_inkless','') ?>
            </span>
                        <i class="a-icon a-icon-text-separator" role="img" aria-label="|"></i>
                        <span class="a-size-mini a-color-state a-text-bold"><?= \yii\helpers\ArrayHelper::getValue($value,'avp_badge_linkless','Verified Purchase') ?></span>
                    </div>
                    <div class="a-row a-spacing-small review-data">
            <span class="a-size-base review-text">
                <div class="a-expander-collapsed-height a-row a-expander-container a-expander-partial-collapse-container"
                     style="max-height:300px">
                    <div class="a-expander-content reviewText review-text-content a-expander-partial-collapse-content">
                        <span class="">
                            <?= \yii\helpers\ArrayHelper::getValue($value,'review_content','') ?>
                        </span>
                    </div>
                    </div>
            </span>
                    </div>
                    <div class="a-popover-preload">
                        <div class="reviewLightboxPopoverContainer">
                            <div class="reviewsLightbox" id="R2Z6OTMTH09WQ2_gallerySection_main"></div>
                        </div>
                    </div>
                    <div class="a-section a-spacing-medium review-image-container" style="display:none;">
                        <div class="review-image-tile-section">
                            <img alt="review image" src="/img/no_image.png" height="88" width="100%">
                        </div>
                    </div>
                    <div class="a-row review-comments cr-vote-action-bar" style="display:none;">
            <span class="cr-vote">
                <div class="a-row a-spacing-small">
                    <span class="a-size-base a-color-tertiary cr-vote-text">25 people found this helpful</span>
                </div>
            </span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
