<?php

use yii\helpers\Html;
use common\widgets\Pjax;
use frontend\widgets\search\SearchResultWidget;

/* @var $this yii\web\View */
/* @var $results array */
/* @var $form common\products\forms\ProductSearchForm */

echo SearchResultWidget::widget([
    'options' => [
        'class' => 'search-2-content',
        'id' => 'wsEbaySearch'
    ],
]);

$js = <<<JS
 $( document).ready(function() {
        $('input.form-check-input').on('change', function(e) {
            var value = $(this).val();
            console.log('clicked, value:'+value);
            // Todo filter
            // vì filter của ebay rất phức tạp, hãy tham khảo v3 để làm mục này,
            // hãy lấy toàn bộ filter đang có, thêm filter mới và push state cho url
            // hãy set attribute checked cho checkbox
        });
    });
JS;

//$this->registerJs($js, \yii\web\View::POS_END);
?>
<div class="row">
    <div class="col-md-3">
        <div class="filter-content">
            <?php
            echo $this->render('_category', [
                'categories' => $results['categories']
            ]);
            ?>
            <?php
            echo $this->render('_filter', [
                'filters' => $results['filters']
            ]);
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="search-content search-2">
            <div class="title-box">
                <div class="left">
                    <div class="text">Tìm kiếm “Bulova” từ</div>
                    <img src="/img/logo_ebay.png" alt=""/>
                    <span>Hiển thị 1-48 của 1.000 kết quả.</span>
                </div>
                <div class="right">
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Sắp xếp theo
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">Action</button>
                            <button class="dropdown-item" type="button">Another action</button>
                            <button class="dropdown-item" type="button">Something else here</button>
                        </div>
                    </div>
                    <ul class="control-page">
                        <li><a href="#" class="control prev"></a></li>
                        <li><a href="#" class="control next"></a></li>
                    </ul>
                </div>
            </div>

            <div class="product-list row">
                <?php
                foreach ($results['products'] as $product) {
                    echo $this->render('_item', [
                        'product' => $product
                    ]);
                }
                ?>
            </div>
        </div>

        <nav aria-label="...">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><span class="more">...</span></li>
                <li class="page-item"><a class="page-link last" href="#">20</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"></a>
                </li>
            </ul>
        </nav>
    </div>
</div>



