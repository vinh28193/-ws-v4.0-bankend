<?php


use yii\helpers\Html;
use frontend\widgets\breadcrumb\BreadcrumbWidget;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $breadcrumbParam array */

$this->beginContent('@frontend/views/layouts/common.php');

echo BreadcrumbWidget::widget(['params' => $breadcrumbParam]);
echo Html::beginTag('div', ['class' => 'container']);
echo $content;
echo Html::endTag('div');
$this->endContent();
?>