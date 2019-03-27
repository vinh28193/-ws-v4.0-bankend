<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modelsMongo\RestApiCall */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RestApiCall';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="RestApiCall-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create RestApiCall', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
