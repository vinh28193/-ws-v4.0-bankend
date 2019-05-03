<?php
/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $url string */
/* @var $name string */
/* @var $sellPrice string */
/* @var $oldPrice string */
/* @var $saleTag integer */
?>


<li>
    <div class="item">
        <div class="thumb">
            <a href="<?= $url ?>">
                <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $product->image ?>"
                alt="<?= $name ?>"
                title="<?= $name ?>">
            </a>
            <?php if ($saleTag > 0) { ?>
                <span class="sale-tag">-<?= $saleTag ?> %</span>
            <?php } ?>
            <div class="bg-hover">
                <a href="<?= $url ?>" class="btn btn-default" title="view-detail">Detail</a>
            </div>
        </div>
        <div class="info">
            <div class="name">
                <a href="<?= $url ?>"><?= $name ?></a>
            </div>
            <div class="price-box">
                <strong><?= $sellPrice ?></strong>
                <span><?= $oldPrice ?></span>
            </div>
        </div>
    </div>
</li>
