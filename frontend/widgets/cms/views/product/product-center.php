<?php

use yii\helpers\Html;
use frontend\widgets\imagelazy\ImageLazyLoadWidget;

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $url string */
/* @var $image string */
/* @var $name string */
/* @var $sellPrice string */
/* @var $oldPrice string */
/* @var $saleTag integer */
?>

<li class="col-md col-6">
    <div class="item">
        <div class="thumb">
            <a href="<?= $url ?>">
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
        </div>
        <div class="info-box">
            <div class="info">
                <div class="name">
                    <a href="<?= $url ?>"><?php echo $name; ?></a>
                </div>
                <div class="price-box">
                    <strong><?= $sellPrice; ?></strong>
                    <div class="old-price"><?= $oldPrice; ?></div>
                    <?php
                    if ($saleTag > 0) {
                        echo '<span class="sale-tag">' . $saleTag . '% OFF</span>';
                    } ?>
                </div>
<!--                <div class="total-price">* Xem giá trọn gói về Việt Nam</div>-->
            </div>
        </div>
    </div>
</li>