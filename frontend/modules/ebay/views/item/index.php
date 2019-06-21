<?php


use yii\widgets\Pjax;
use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */

$this->params = ['Home' => '/','eBay' => '/ebay.html', $item->item_name => 'javascript:void(0);'];
$this->title = Yii::t('frontend','Detail Product Ebay |').' '.$item->item_name;

echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'ebay-item-detail',
        'class' => 'row'
    ]
]);

