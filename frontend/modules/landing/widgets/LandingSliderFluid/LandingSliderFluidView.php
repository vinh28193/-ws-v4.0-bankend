<?php
/**
 * @var $this \yii\web\View
 * @var $categories array
 * @var $images \common\models\cms\WsImage[]
 */

use frontend\widgets\cms\SlideWidget;


?>
<div class="main-tabs text-right">
    <div class="container">
        <ul class="list-inline">
            <?php if (isset($categories)) {
                foreach ($categories as $key => $cat) { ?>
                    <li class=""><a href="<?= $cat['url'] ?>"><?= $cat['name'] ?></a>
                    </li>
                <?php }
            } ?>

        </ul>
    </div>
</div>


<?php

echo SlideWidget::widget([
    'list_images' => $images,
    'options' => [
        'id' => 'home-slide'
    ],
    'owlCarouselOptions' => [
        'slideSpeed' => 300,
        'paginationSpeed' => 400,
        'loop' => !0,
        'items' => 1,
        'itemsDesktop' => !1,
        'itemsDesktopSmall' => !1,
        'itemsTablet' => !1,
        'itemsMobile' => !1,
        'autoplay' => 1e3
    ]
]);
?>


