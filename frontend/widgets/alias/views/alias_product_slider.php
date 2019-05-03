<?php

use frontend\widgets\cms\ProductWidget;

/* @var $this yii\web\View */
/* @var $wsCategoryGroups array */
/* @var $wsProductGroups array */
/* @var $index string */
/* @var $item array */

?>


<div class="ebay-sub-slider">
    <div id="<?= $index; ?>" class="owl-carousel owl-theme">
        <?php foreach ($wsProductGroups['wsProducts'] as $product) {
            echo ProductWidget::widget([
                'product' => $product,
                'type' => ProductWidget::TYPE_ALIAS
            ]);
        }
        ?>
    </div>
</div>
