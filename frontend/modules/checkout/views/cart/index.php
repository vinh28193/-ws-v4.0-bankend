<?php

use yii\helpers\Html;
use frontend\widgets\cart\CartWidget;

/* @var yii\web\View $this */
/* @var array $items */
/* @var string $cartContent */
/* @var string|null $uuid */


?>

<div class="row">
    <div class="col-md-12">
        <?php
        echo CartWidget::widget([
            'items' => $items,
            'uuid' => $uuid,
            'options' => [
                'class' => 'cart-box'
            ]
        ]);
        ?>
    </div>
</div>