<?php

use common\helpers\WeshopHelper;
use common\models\cms\WsProduct;
use frontend\widgets\breadcrumb\BreadcrumbWidget;
use frontend\widgets\cms\ProductWidget;
use frontend\widgets\search\SearchResultWidget;

/**
 * @var $data array
 * @var $form \common\products\forms\ProductSearchForm
 */
$keyword = Yii::$app->request->get('keyword');
if($form->type == 'ebay'){
    $this->params = ['Home' => '/','eBay Search' => '/', $keyword => '/search/'.$keyword.'.html'];
}else{
    $this->params = ['Home' => '/','Amazon Search' => '/', $keyword => '/search/'.$keyword.'.html'];
}
echo SearchResultWidget::widget([
    'results' => \yii\helpers\ArrayHelper::getValue($data,'amazon'),
    'form' => $form,
    'options' => [
        'class' => 'search-2-content',
        'id' => 'wsAmazonSearch'
    ],
]);
?>
