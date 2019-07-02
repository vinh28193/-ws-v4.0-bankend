<?php
/**
 * @var \common\products\RelateProduct $productSuggest
 * @var \yii\web\View $this
 */
$this->title = 'Not found page!';
$js = <<<JS
$(document).ready(function() {
 ws.ajax('/portal/v-p',{
        type: 'POST',
        dataType: 'json',
        data: {fingerprint: ws.getFingerprint()},
        loading: true,
        success: function (result) {
            if(result.success == true ){
                $("#viewed-product" ).css( "display", "block" );
                $('#viewed-product').html(result.data.content);
                $(".owl-carousel").owlCarousel({
                    loop:false,
                    margin:10,
                    nav:true,
                    arrows: true,
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
            }else {
               $( "#viewed-product" ).css( "display", "none" );
            }
        }
        });
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);

?>

<div class="row error-404">
    <div class="col-md-6 m-auto row">
        <div class="col-12 col-sm-12 col-md-6" style="margin-top: 10%">
            <p class="title title-error">We looked everywhere.</p>
            <p class="content-error">Looks like this page is missing. If you still need help, visit our help pages</p>
            <p class="content-error"><a href="/" class="btn btn-amazon" style="border-radius: 0px">Go to home page</a></p>
        </div>
        <div class="col-12 col-sm-12 col-md-6">
            <img src="/images/404.png">
        </div>
    </div>
</div>
<div  id="product-relate" class="product-viewed product-list box-shadow">
    <div class="title"><?= Yii::t('frontend','Top views on eBay') ?>:</div>
    <div class="owl-carousel owl-theme">
        <?php
        if($productSuggest) {
            foreach ($productSuggest as $product) {
                $percent = $product->retail_price && $product->sell_price ? round((($product->retail_price - $product->sell_price) / $product->retail_price) * 100, 0) : 0;
                echo \frontend\widgets\item\RelateProduct::widget(['product' => $product,'portal' => 'ebay']);
            }
        }?>
    </div>
</div>

<div id="viewed-product" class="product-viewed product-list box-shadow">
    <div class="center" style="display:block; margin: auto; width: 7%;padding: 10px;">
        <div>
            <img src="/img/gif/loading.gif">
        </div>
    </div>
</div>