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




