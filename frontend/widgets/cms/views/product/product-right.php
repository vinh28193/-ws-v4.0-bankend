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

<li>
    <div class="item">
        <a href="#">
            <div class="name"><?= $name; ?></div>
            <div class="info">
                <div class="price"><?= $sellPrice; ?></div>
                <div class="old-price"><?= $oldPrice; ?></div>
                <?php
                if ($saleTag > 0) {
                    echo '<span class="sale-tag">' . $saleTag . '% OFF</span>';
                }
                ?>
            </div>
            <div class="thumb">
                <span>
                    <?php
                        if ($iphoneOld) {
                            echo Html::img($image);
                        }else{
                            echo ImageLazyLoadWidget::widget([
                                'src' => $image
                            ]);
                        }
                    ?>
                </span>
            </div>
        </a>
    </div>
</li>

