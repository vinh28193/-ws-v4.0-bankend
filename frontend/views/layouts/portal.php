<?php

use frontend\assets\FancyboxPlusAsset;
use frontend\widgets\alias\AliasWidget;
use frontend\widgets\breadcrumb\BreadcrumbWidget;
use frontend\widgets\search\SearchBoxWidget;


/* @var $this \yii\web\View */
/* @var $content string */
/* @var $portal string */

$this->beginContent('@frontend/views/layouts/common.php');
FancyboxPlusAsset::register($this);
$js = <<<JS
$(document).ready(function() {
  setTimeout(function () { 
        ws.ajax('/portal/viewed-products',{
        type: 'POST',
        dataType: 'json',
        data: {fingerprint: ws.getFingerprint()},
        loading: true,
        success: function (result) {
            // console.log(result);  console.log(result.success);
            if(result.success){
                $('.viewed-product').html(result.data.content);
                $(".owl-carousel").owlCarousel({
                    loop:false,
                    margin:10,
                    nav:true,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:3
                        },
                        1000:{
                            items:5
                        }
                    }
                });
            }
        }
        });
    }, 1000 * 1);
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);

echo BreadcrumbWidget::widget(['params' => $this->params]);
?>
<!--    <div class="keep-navbar <?/*= strtolower($portal) == 'amazon-jp' ? 'amazon' : strtolower($portal) */?> other-page">
        <div class="container">
            <?php
/*            echo AliasWidget::widget(['type' => $portal]);
            echo SearchBoxWidget::widget([])
            */?>
        </div>
    </div>-->
    <div class="container">
        <?= $content; ?>


        <div class="product-viewed product-list detail-block-2 viewed-product">
            <div class="center" style="display:block; margin: auto; width: 7%;padding: 10px;">
                <div>
                    <img src="/img/gif/loading.gif">
                </div>
            </div>
        </div>
    </div>

<?php

$this->endContent();
