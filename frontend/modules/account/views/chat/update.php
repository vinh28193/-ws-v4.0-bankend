<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\ChatMongoWs */

$this->title = 'Update Chat Mongo Ws: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Chat Mongo Ws', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chat-mongo-ws-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
