<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Logrouteapi */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Log API CALL', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             '_id',
            'success',
            'created_at',
            'updated_at',
            'path',
            'data',
            'date',
            'user_id',
            'user_email',
            'user_name',
            'user_app',
            'user_request_suorce',
            'request_ip',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
