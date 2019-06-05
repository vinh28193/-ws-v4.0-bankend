<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%purchase_order}}".
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
 * @property int $updated_by
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
        return '{{%purchase_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_item', 'total_quantity', 'total_paid_seller', 'total_changing_price', 'purchase_amount_buck'], 'number'],
            [['receive_warehouse_id', 'purchase_account_id', 'purchase_card_id', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [['updated_by'], 'required'],
            [['note', 'purchase_order_number', 'total_type_changing', 'purchase_card_number', 'transaction_payment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'note' => Yii::t('db', 'Note'),
            'purchase_order_number' => Yii::t('db', 'Purchase Order Number'),
            'total_item' => Yii::t('db', 'Total Item'),
            'total_quantity' => Yii::t('db', 'Total Quantity'),
            'total_paid_seller' => Yii::t('db', 'Total Paid Seller'),
            'total_changing_price' => Yii::t('db', 'Total Changing Price'),
            'total_type_changing' => Yii::t('db', 'Total Type Changing'),
            'receive_warehouse_id' => Yii::t('db', 'Receive Warehouse ID'),
            'purchase_account_id' => Yii::t('db', 'Purchase Account ID'),
            'purchase_card_id' => Yii::t('db', 'Purchase Card ID'),
            'purchase_card_number' => Yii::t('db', 'Purchase Card Number'),
            'purchase_amount_buck' => Yii::t('db', 'Purchase Amount Buck'),
            'transaction_payment' => Yii::t('db', 'Transaction Payment'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'updated_by' => Yii::t('db', 'Updated By'),
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
