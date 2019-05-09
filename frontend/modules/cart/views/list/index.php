<?php

use yii\helpers\Html;
use frontend\widgets\cart\CartWidget;

/* @var yii\web\View $this */
/* @var array $items */


echo CartWidget::widget([
    'items' => $items,
    'options' => [
        'id' => 'cartContent',
        'class' => 'cart-content'
    ]
]);

$js = <<< JS
   $(document).on('click','button.button-quantity-up,button.button-quantity-down', function(event) {
        alert('clicked');
   }) ;
JS;
$this->registerJs($js);
?>

