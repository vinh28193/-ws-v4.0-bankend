<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%draft_data_tracking}}".
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $product_id
 * @property int $order_id
 * @property int $manifest_id
 * @property string $manifest_code
 * @property int $quantity
 * @property double $weight
 * @property double $dimension_l
 * @property double $dimension_w
 * @property double $dimension_h
 * @property string $purchase_invoice_number
 * @property int $number_get_detail Số lần chạy api lấy detail
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $type_tracking split, normal, unknown
 * @property string $tracking_merge List tracking đã được merge
 * @property string $item_name
 * @property string $seller_refund_amount Sô tiền seller hoàn
 * @property string $ws_tracking_code Mã tracking của weshop
 * @property string $image
 * @property int $stock_in_us
 * @property int $stock_out_us
 * @property int $stock_in_local
 * @property int $stock_out_local
 */
class DraftDataTracking extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%draft_data_tracking}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code'], 'required'],
            [['product_id', 'order_id', 'manifest_id', 'quantity', 'number_get_detail', 'created_at', 'updated_at', 'created_by', 'updated_by', 'stock_in_us', 'stock_out_us', 'stock_in_local', 'stock_out_local'], 'integer'],
            [['weight', 'dimension_l', 'dimension_w', 'dimension_h', 'seller_refund_amount'], 'number'],
            [['tracking_merge', 'item_name', 'image'], 'string'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status', 'type_tracking', 'ws_tracking_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'tracking_code' => Yii::t('db', 'Tracking Code'),
            'product_id' => Yii::t('db', 'Product ID'),
            'order_id' => Yii::t('db', 'Order ID'),
            'manifest_id' => Yii::t('db', 'Manifest ID'),
            'manifest_code' => Yii::t('db', 'Manifest Code'),
            'quantity' => Yii::t('db', 'Quantity'),
            'weight' => Yii::t('db', 'Weight'),
            'dimension_l' => Yii::t('db', 'Dimension L'),
            'dimension_w' => Yii::t('db', 'Dimension W'),
            'dimension_h' => Yii::t('db', 'Dimension H'),
            'purchase_invoice_number' => Yii::t('db', 'Purchase Invoice Number'),
            'number_get_detail' => Yii::t('db', 'Number Get Detail'),
            'status' => Yii::t('db', 'Status'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'created_by' => Yii::t('db', 'Created By'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'type_tracking' => Yii::t('db', 'Type Tracking'),
            'tracking_merge' => Yii::t('db', 'Tracking Merge'),
            'item_name' => Yii::t('db', 'Item Name'),
            'seller_refund_amount' => Yii::t('db', 'Seller Refund Amount'),
            'ws_tracking_code' => Yii::t('db', 'Ws Tracking Code'),
            'image' => Yii::t('db', 'Image'),
            'stock_in_us' => Yii::t('db', 'Stock In Us'),
            'stock_out_us' => Yii::t('db', 'Stock Out Us'),
            'stock_in_local' => Yii::t('db', 'Stock In Local'),
            'stock_out_local' => Yii::t('db', 'Stock Out Local'),
        ];
    }
}
