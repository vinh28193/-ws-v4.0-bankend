<?php

use frontend\widgets\cms\ProductWidget;

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $categories array */
/* @var $products array */

?>
<div class="deal-shock">
    <div class="container">
        <div class="deal-title-box">
            <div class="title">Deal sốc trong ngày</div>
            <ul class="title-breadcrumb mobile-hide">
                <?php if (!empty($categories)) {
                    foreach ($categories as $key => $val) { ?>
                        <li><a href="<?= $val['url'] ?>"><?= $val['name'] ?></a></li>
                    <?php }
                } ?>
            </ul>
            <a href="<?= $block['url'] ?>" class="see-all mobile-hide"><span>Xem tất cả</span><i class="see-ico"></i></a>
        </div>
        <div class="deal-content">
            <ul class="row">
                <?php
                foreach ($products as $key => $val) {
                    echo ProductWidget::widget([
                        'product' => $val,
                        'type' => ProductWidget::TYPE_CENTER
                    ]);
                }
                ?>
            </ul>
        </div>
    </div>
</div>
