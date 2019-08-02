<?php


use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */
$cate_param = \frontend\widgets\breadcrumb\BreadcrumbWidget::GenerateCategory(implode(',',$item->categories));
if($cate_param){
    $param = array_merge(['Home' => '/','eBay' => '/ebay.html'],$cate_param);
    $param = array_merge($param,[$item->item_name => 'javascript:void(0);']);
}else{
    $param = ['Home' => '/','eBay' => '/ebay.html', $item->item_name => 'javascript:void(0);'];
}
$this->params = $param;
$js = <<<JS
$(document).ready(function () {
        $.ajax({
            url: '/ebay/item/suggest',
            method: 'post',
            async: true,
            data: {sku: '$item->item_id'},
            success: function (res) {
                if(res.content){
                    $('#product-relate').css('display','block');
                    $("#product-relate .owl-carousel").remove();
                    var text = '<div class="owl-carousel owl-theme">';
                    text += res.content;
                    text += '</div>';
                    $('#product-relate').append(text);
                    $(".owl-carousel").owlCarousel({
                        slideSpeed : 300,
                        paginationSpeed : 400,
                        loop: true,
                        nav: true,
                        autoplay: 1000,
                        responsive : {
                            0: {
                                items: 3,
                            },
                            575: {
                                items: 4,
                            },
                            768: {
                                items: 5,
                            }
                        },
                        dots: false
                    });
                }else {
                    $('#product-relate').css('display','none');
                }
            }
        });
    });
JS;
$this->registerJs($js);

echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'ebay-item-detail',
        'class' => 'row'
    ]
]);
if ($item->customer_feedback && false) {
    ?>
    <div class="detail-block-2 box-shadow">
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?= Yii::t('frontend', 'Customer Reviewed On {portal}', ['portal' => '<a href="//ebay.com" target="_blank">eBay.com</a>']) ?>
                    :
                </div>
            </div>
            <div class="col-md-12 row rating-feedback">
                <?php
                foreach ($item->customer_feedback as $value) {
                    ?>
                    <div class="col-md-12">
                        <div class="avatar-feedback">
                            <span>
                                <img src="/img/no_image.png">
                            </span>
                            <span class="time-feedback">
                                <?= Yii::t('frontend',$value['day_review']) ?>
                            </span>
                        </div>
                        <div class="content-feedback"><?= $value['review_content'] ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}
?>
