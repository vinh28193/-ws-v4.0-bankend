<?php


use yii\widgets\Pjax;
use frontend\widgets\item\ItemDetailWidget;

/* @var $this yii\web\View */
/* @var $item \common\products\BaseProduct */
$cate_param = \frontend\widgets\breadcrumb\BreadcrumbWidget::GenerateCategory(implode(',',$item->categories));
if($cate_param){
    $param = array_merge(['Home' => '/','Amazon' => '/amazon.html'],$cate_param);
    $param = array_merge($param,[$item->item_name => 'javascript:void(0);']);
}else{
    $param = ['Home' => '/','Amazon' => '/amazon.html', $item->item_name => 'javascript:void(0);'];
}
$this->params = $param;
$this->title = Yii::t('frontend','{name} | Product Amazon' , ['name' => $item->item_name]);
echo ItemDetailWidget::widget([
    'item' => $item,
    'options' => [
        'id' => 'Amazon-item-detail',
        'class' => 'row'
    ]
]);
if($item->customer_feedback){
    echo \frontend\widgets\item\FeedBackWidget::widget(['portal' => $item->type , 'array' => $item->customer_feedback]);
}
?>

