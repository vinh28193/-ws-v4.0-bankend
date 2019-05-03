<?php

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
                    <img src="<?= $image; ?>" alt="<?= $name; ?>" title="<?= $name; ?>"/>
                </span>
            </div>
        </a>
    </div>
</li>

