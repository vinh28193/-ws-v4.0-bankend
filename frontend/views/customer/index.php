<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'last_name',
            'email:email',
            'phone',
            //'username',
            //'password_hash',
            //'gender',
            //'birthday',
            //'avatar',
            //'link_verify',
            //'email_verified:email',
            //'phone_verified',
            //'last_order_time',
            //'note_by_employee:ntext',
            //'type_customer',
            //'access_token',
            //'auth_client',
            //'verify_token',
            //'reset_password_token',
            //'store_id',
            //'active_shipping',
            //'total_xu',
            //'total_xu_start_date',
            //'total_xu_expired_date',
            //'usable_xu',
            //'usable_xu_start_date',
            //'usable_xu_expired_date',
            //'last_use_xu',
            //'last_use_time',
            //'last_revenue_xu',
            //'last_revenue_time',
            //'verify_code',
            //'verify_code_expired_at',
            //'verify_code_count',
            //'verify_code_type',
            //'created_at',
            //'updated_at',
            //'active',
            //'remove',
            //'version',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
