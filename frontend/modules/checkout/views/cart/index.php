<?php

use yii\helpers\Html;
use frontend\widgets\cart\CartWidget;

/* @var yii\web\View $this */
/* @var array $items */
/* @var string $cartContent */
/* @var string|null $uuid */

echo CartWidget::widget([
    'items' => $items,
    'uuid' => $uuid,
    'options' => [
        'id' => 'cartContent',
        'class' => 'cart-content'
    ]
]);

?>

