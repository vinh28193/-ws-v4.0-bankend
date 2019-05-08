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
])

?>

