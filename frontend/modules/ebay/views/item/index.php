<?php


use yii\widgets\Pjax;
use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */


Pjax::begin([
    'options' => [
        'id' => 'ebay-item',
        'class' => 'detail-content'
    ]
]);

echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'ebay-item-detail',
        'class' => 'row'
    ]
]);
Pjax::end(); ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('.slick-slider').slick({
            infinite: true,
            vertical: true,
            arrows: true,
            prevArrow: $('.slider-prev'),
            nextArrow: $('.slider-next'),
            slidesToShow: 5
        });

        $('#detail-big-img').ezPlus({
            imageCrossfade: true,
            easing: true,
            scrollZoom: true,
            cursor: 'zoom-in',
            gallery: 'detail-slider',
            galleryActiveClass: 'active'
        });
    });
</script> -->