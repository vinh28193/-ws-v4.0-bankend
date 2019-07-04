<?php
use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 */
$this->beginContent('@frontend/views/layouts/common.php');
//echo \frontend\widgets\breadcrumb\BreadcrumbWidget::widget(['params' => $this->params ? $this->params : []]);
echo \frontend\widgets\breadcrumb\BreadcrumbWidget::widget(['params' => []]);
?>

<div class="container">
    <?= $content ?>
</div>

<?php $this->endContent(); ?>