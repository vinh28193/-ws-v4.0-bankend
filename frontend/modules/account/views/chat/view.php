<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\ChatMongoWs */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Chat Mongo Ws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chat-mongo-ws-view">

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
            'success',
            'created_at',
            'updated_at',
            'date',
            'user_id',
            'user_email',
            'user_name',
            'user_app',
            'user_request_suorce',
            'request_ip',
            'message',
            'user_avatars',
            'Order_path',
            'is_send_email_to_customer',
            'type_chat',
            'is_customer_vew',
            'is_employee_vew',
        ],
    ]) ?>

</div>
