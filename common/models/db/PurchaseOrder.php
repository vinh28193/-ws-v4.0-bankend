<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "purchase_order".
 *
 * @property int $id
 * @property string $note Ghi chú đơn hàng
 * @property string $purchase_order_number mã đơn hàng trên ebay,amazon ...
 * @property string $total_item Tổng số lượng item có
 * @property string $total_quantity Tổng số lượng
 * @property string $total_paid_seller Tiền đã thanh toán cho người bán. Đơn vị: usd, jpy .v.v.
 * @property string $total_changing_price số tiền chênh lệch giá . amount_purchase - amount_order
 * @property string $total_type_changing kiểu chênh lệch: up, down
 * @property int $receive_warehouse_id Id kho nhận hàng.
 * @property int $purchase_account_id id tài khoản mua hàng.
 * @property int $purchase_card_id id thẻ mua thanh toán.
 * @property string $purchase_card_number số thẻ thanh toán.
 * @property string $purchase_amount_buck số tiền buck thành toán.
 * @property string $transaction_payment Mã giao dịch thanh toán paypal.
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PurchaseProduct[] $purchaseProducts
 */
class PurchaseOrder extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_item', 'total_quantity', 'total_paid_seller', 'total_changing_price', 'total_type_changing', 'purchase_amount_buck', 'transaction_payment'], 'number'],
            [['receive_warehouse_id', 'purchase_account_id', 'purchase_card_id', 'created_at', 'updated_at'], 'integer'],
            [['note', 'purchase_order_number', 'purchase_card_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'note' => 'Note',
            'purchase_order_number' => 'Purchase Order Number',
            'total_item' => 'Total Item',
            'total_quantity' => 'Total Quantity',
            'total_paid_seller' => 'Total Paid Seller',
            'total_changing_price' => 'Total Changing Price',
            'total_type_changing' => 'Total Type Changing',
            'receive_warehouse_id' => 'Receive Warehouse ID',
            'purchase_account_id' => 'Purchase Account ID',
            'purchase_card_id' => 'Purchase Card ID',
            'purchase_card_number' => 'Purchase Card Number',
            'purchase_amount_buck' => 'Purchase Amount Buck',
            'transaction_payment' => 'Transaction Payment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::className(), ['purchase_order_id' => 'id']);
    }
}
