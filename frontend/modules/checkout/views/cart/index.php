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
//$this->registerJs($js);

$jsga = <<<JS
$(document).ready(function() {
    var client = new ClientJS();
            var _fingerprint = client.getFingerprint();
            var data = {
                fingerprint: _fingerprint,
                path : window.location.pathname
            };
            // /cms/home/u
  setTimeout(function () { 
        ws.ajax('/frontend/u',{
        type: 'POST',
        dataType: 'json',
        data: data,
        loading: true,
        success: function (result) {
            console.log(result);  console.log(result.success); 
        }
        });
    }, 1000 * 1);
});
JS;
$this->registerJs($jsga, \yii\web\View::POS_END);

?>

