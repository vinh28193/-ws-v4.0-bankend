<?php


use yii\widgets\Pjax;
use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */
$this->params = ['Home' => '/','Amazon' => '/amazon.html', $item->item_name => '#'];
$this->title = Yii::t('frontend','Detail Product Amazon |').' '.$item->item_name;
echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'Amazon-item-detail',
        'class' => 'row'
    ]
]);
if($item->customer_feedback){
//    echo \frontend\widgets\item\FeedBackWidget::widget(['portal' => $item->type , 'array' => $item->customer_feedback]);
}
?>

