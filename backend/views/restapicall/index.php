<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\components\RestApiCall */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RestApiCall';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create RestApiCall', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'success',
            'timestamp',
            'path',
            'data',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
