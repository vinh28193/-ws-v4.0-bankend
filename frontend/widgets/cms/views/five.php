<?php

use frontend\widgets\cms\ProductWidget;

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $products array */
?>

<div class="col-md-6">
    <div class="title-box">
        <div class="title"><?= $block['name']; ?></div>
        <a href="<?= $block['url']; ?>" class="see-all">Xem tất cả</a>
    </div>
    <div class="pd-banner">
        <a href="<?= $block['image_url']; ?>">
            <img src="<?= $block['image']; ?>" alt="<?= $block['name']; ?>"
                 title="<?= $block['name']; ?>">
        </a>
    </div>
    <div class="pd-content">
        <div class="right">
            <div class="right-1">
                <ul>
                    <?php if (isset($products)) {
                        foreach ($products as $key => $val) {
                            if ($key < 2) {
                                echo ProductWidget::widget([
                                    'product' => $val,
                                    'type' => ProductWidget::TYPE_RIGHT
                                ]);
                            }
                        }
                    } ?>
                </ul>
            </div>
            <div class="right-2">
                <ul>
                    <?php if (isset($products)) {
                        foreach ($products as $key => $val) {
                            if ($key >= 2 && $key < 5) {
                                echo ProductWidget::widget([
                                    'product' => $val,
                                    'type' => ProductWidget::TYPE_LEFT
                                ]);
                            }
                        }
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
