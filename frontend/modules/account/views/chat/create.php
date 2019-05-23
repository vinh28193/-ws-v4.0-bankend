<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\ChatMongoWs */

$this->title = 'Create Chat Mongo Ws';
$this->params['breadcrumbs'][] = ['label' => 'Chat Mongo Ws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-mongo-ws-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
