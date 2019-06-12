<?php

use yii\helpers\Html;
use frontend\widgets\imagelazy\ImageLazyLoadWidget;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $iphoneOld boolean */
/* @var $url string */
/* @var $image string */
/* @var $name string */
/* @var $sellPrice string */
/* @var $oldPrice string */
/* @var $classCustom string */
/* @var $saleTag integer */
?>
<div class="<?= $classCustom?>">
    <a href="<?= $url ?>" class="item">
        <div class="thumb">
            <?php
            if ($iphoneOld) {
                echo Html::img($image);
            }else{
                echo ImageLazyLoadWidget::widget([
                    'src' => $image
                ]);
            }
            ?>
        </div>
        <div class="info">
            <div class="rate text-orange">
                <i class="la la-star"></i>
                <i class="la la-star"></i>
                <i class="la la-star"></i>
                <i class="la la-star-half-o"></i>
                <i class="la la-star-o"></i>
                <span>(--)</span>
            </div>
            <div class="name"><?php echo $name; ?></div>
            <div class="price">
                <strong><?= $sellPrice; ?></strong>
                <span><?= $oldPrice ? $oldPrice : '' ?></span>
                <span class="<?= $saleTag > 0 ? 'sale-tag' : '' ?>"><?= $saleTag > 0 ? $saleTag.'% OFF' : '' ?></span>
            </div>
        </div>
    </a>
</div>
