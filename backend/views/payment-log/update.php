<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\components\PaymentLogWS */

$this->title = 'Update PaymentLogWS: ' . ' ' . $model->success;
$this->params['breadcrumbs'][] = ['label' => 'PaymentLogWS', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->success, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="PaymentLogWS-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
