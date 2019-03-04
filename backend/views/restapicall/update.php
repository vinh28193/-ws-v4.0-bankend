<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\components\RestApiCall */

$this->title = 'Update RestApiCall: ' . ' ' . $model->success;
$this->params['breadcrumbs'][] = ['label' => 'RestApiCall', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->success, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
