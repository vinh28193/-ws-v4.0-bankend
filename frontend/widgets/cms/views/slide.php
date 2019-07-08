<?php
/**
 * @var $images \common\models\cms\WsImage[]
 */
?>
<div id="slides" class="carousel slide" data-ride="carousel">
    <!-- The slideshow -->
    <div class="carousel-inner">
        <?php foreach($images as $k => $image) {?>
            <div class="carousel-item <?= $k == 0 ? 'active' : '' ?>">
                <a href="<?= $image->link ? $image->link: 'javascript: void(0);' ?>">
                    <img src="<?= $image->domain.$image->origin_src ?>"
                         alt="<?= $image->name ?>">
                </a>
            </div>
        <?php }?>
    </div>

    <!-- Left and right controls -->
    <a class="carousel-control-prev" href="#slides" data-slide="prev">
        <span class="la la-angle-left btn-change-slides"></span>
    </a>
    <a class="carousel-control-next" href="#slides" data-slide="next">
        <span class="la la-angle-right btn-change-slides"></span>
    </a>
</div>