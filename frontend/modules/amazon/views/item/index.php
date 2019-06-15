<?php


use yii\widgets\Pjax;
use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */
$this->params = ['Home' => '/','Amazon' => '/amazon.html', $item->item_name => '#'];

echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'Amazon-item-detail',
        'class' => 'row'
    ]
]);

