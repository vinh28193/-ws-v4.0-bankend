<?php


use yii\widgets\Pjax;
use frontend\widgets\WeshopItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */


Pjax::begin([
    'options' => [
        'id' => 'ebay-item',
        'class' => 'detail-content'
    ]
]);

echo WeshopItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'ebay-item-detail',
        'class' => 'row'
    ]
]);
Pjax::end(); ?>

