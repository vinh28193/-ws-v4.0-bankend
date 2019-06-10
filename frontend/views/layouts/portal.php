<?php

use frontend\assets\FancyboxPlusAsset;
use frontend\widgets\alias\AliasWidget;
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/">Weshop Global</a></li>
                <li class="breadcrumb-item"><a href="/<?= strtolower($portal); ?>.html"> <?php if( strtolower($portal) == "ebay") { echo 'Shop Ebay';}  if( strtolower($portal) == "amazon") { echo 'Shop Amazon';}  if( strtolower($portal) == "amazon-jp") { echo 'Shop Amazon Japan';} if( strtolower($portal) == "amazon-uk") { echo 'Shop Amazon Japan';} ?>   </a></li>
                <?= $Ekey = Yii::$app->request->get('keyword','') ?>
                <?php if ($Ekey != ''){?>
                    <li class="breadcrumb-item active">Tìm kiếm từ khóa <?php  echo $Ekey; ?></li>
                <?php } ?>
            </ol>
        </nav>
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
