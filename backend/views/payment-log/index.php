<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modelsMongo\PaymentLogWS */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PaymentLogWS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="PaymentLogWS-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create PaymentLogWS', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            //'created_at',
            //'updated_at',
            'date',

            //'user_id',
            'user_email',
            'user_name',
            //'user_avatar',

            //'user_app',
            'user_request_suorce',
            'request_ip',

            'Role','data_input','data_output',
            'action_path',
            //'status' ,
            //'LogTypPaymentWs','OrderId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
