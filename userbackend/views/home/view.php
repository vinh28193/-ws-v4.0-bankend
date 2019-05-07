<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'ordercode',
            'store_id',
            'type_order',
            'customer_id',
            'customer_type',
            'portal',
            'utm_source',
            'new',
            'purchase_start',
            'purchased',
            'seller_shipped',
            'stockin_us',
            'stockout_us',
            'stockin_local',
            'stockout_local',
            'at_customer',
            'returned',
            'cancelled',
            'lost',
            'current_status',
            'is_quotation',
            'quotation_status',
            'quotation_note',
            'receiver_email:email',
            'receiver_name',
            'receiver_phone',
            'receiver_address',
            'receiver_country_id',
            'receiver_country_name',
            'receiver_province_id',
            'receiver_province_name',
            'receiver_district_id',
            'receiver_district_name',
            'receiver_post_code',
            'receiver_address_id',
            'note_by_customer:ntext',
            'note:ntext',
            'seller_id',
            'seller_name',
            'seller_store:ntext',
            'total_final_amount_local',
            'total_amount_local',
            'total_origin_fee_local',
            'total_price_amount_origin',
            'total_paid_amount_local',
            'total_refund_amount_local',
            'total_counpon_amount_local',
            'total_promotion_amount_local',
            'total_fee_amount_local',
            'total_custom_fee_amount_local',
            'total_origin_tax_fee_local',
            'total_origin_shipping_fee_local',
            'total_weshop_fee_local',
            'total_intl_shipping_fee_local',
            'total_delivery_fee_local',
            'total_packing_fee_local',
            'total_inspection_fee_local',
            'total_insurance_fee_local',
            'total_vat_amount_local',
            'exchange_rate_fee',
            'exchange_rate_purchase',
            'currency_purchase',
            'payment_type',
            'sale_support_id',
            'support_email:email',
            'is_email_sent:email',
            'is_sms_sent',
            'difference_money',
            'coupon_id',
            'revenue_xu',
            'xu_count',
            'xu_amount',
            'xu_time',
            'xu_log',
            'promotion_id',
            'total_weight',
            'total_weight_temporary',
            'created_at',
            'updated_at',
            'purchase_assignee_id',
            'purchase_order_id:ntext',
            'purchase_transaction_id:ntext',
            'purchase_amount',
            'purchase_account_id:ntext',
            'purchase_account_email:ntext',
            'purchase_card:ntext',
            'purchase_amount_buck',
            'purchase_amount_refund',
            'purchase_refund_transaction_id:ntext',
            'total_quantity',
            'total_purchase_quantity',
            'remove',
            'version',
            'mark_supporting',
            'supported',
            'ready_purchase',
            'supporting',
            'check_update_payment',
        ],
    ]) ?>

</div>
