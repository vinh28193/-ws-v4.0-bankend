<?php

/* @var $this yii\web\View */

use frontend\widgets\imagelazy\ImageLazyLoadWidget;
use yii\helpers\Html;

/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $url string */
/* @var $image string */
/* @var $name string */
/* @var $sellPrice string */
/* @var $oldPrice string */
/* @var $saleTag integer */

?>

<div class="item">
    <div class="item-box">
        <div class="thumb">
            <a href="#">
                <?php
                if ($iphoneOld) {
                    echo Html::img($image);
                }else{
                    echo ImageLazyLoadWidget::widget([
                        'src' => $image
                    ]);
                }
                ?>
            </a>
            <?php
                if ($saleTag > 0) {
                    echo '<span class="sale-tag">' . $saleTag . '% <span>Sale</span></span>';
                } ?>
        </div>
        <div class="info">
            <div class="name">
                <a href="#"><?php echo $name; ?></a>
            </div>
            <div class="price"><?= $sellPrice; ?></div>
            <div class="old-price"><?= $oldPrice; ?></div>
        </div>
    </div>
</div>
