<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'email:email',
            'phone',
            'username',
            'password_hash',
            'gender',
            'birthday',
            'avatar',
            'link_verify',
            'email_verified:email',
            'phone_verified',
            'last_order_time',
            'note_by_employee:ntext',
            'type_customer',
            'access_token',
            'auth_client',
            'verify_token',
            'reset_password_token',
            'store_id',
            'active_shipping',
            'total_xu',
            'total_xu_start_date',
            'total_xu_expired_date',
            'usable_xu',
            'usable_xu_start_date',
            'usable_xu_expired_date',
            'last_use_xu',
            'last_use_time',
            'last_revenue_xu',
            'last_revenue_time',
            'verify_code',
            'verify_code_expired_at',
            'verify_code_count',
            'verify_code_type',
            'created_at',
            'updated_at',
            'active',
            'remove',
            'version',
        ],
    ]) ?>

</div>
