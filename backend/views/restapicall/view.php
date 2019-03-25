<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\RestApiCall */

$this->title = $model->success;
$this->params['breadcrumbs'][] = ['label' => 'RestApiCall', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->_id;
?>
<div class="RestApiCall-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'path',
            'data',
            'success',
            'user_id',
            'user_email',
            'user_name',
            'user_app',
            'user_request_suorce',
            'request_ip',
            'date',
            'timestamp',
        ],
    ]) ?>

</div>
