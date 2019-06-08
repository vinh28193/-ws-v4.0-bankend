<?php

use yii\helpers\Html;
use frontend\widgets\cart\PjaxCartWidget;

/* @var yii\web\View $this */
/* @var array $items */

echo PjaxCartWidget::widget([
    'options' => [
        'id' => 'cartContent',
        'class' => 'cart-content'
    ]
]);

?>

