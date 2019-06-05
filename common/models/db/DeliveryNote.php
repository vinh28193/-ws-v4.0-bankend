<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%delivery_note}}".
 *
 * @property int $id
 * @property string $delivery_note_code Mã kiện của weshop
 * @property string $tracking_seller mã giao dịch của weshop
 * @property string $order_ids List mã order cách nhau bằng dấu ,
 * @property string $tracking_reference_1 mã tracking tham chiếu 1
 * @property string $tracking_reference_2 mã tracking tham chiếu 2
 * @property string $manifest_code mã lô hàng
 * @property double $delivery_note_weight cân nặng tịnh của cả gói , đơn vị gram
 * @property double $delivery_note_change_weight cân nặng quy đổi của cả gói , đơn vị gram
 * @property double $delivery_note_dimension_l chiều dài của cả gói , đơn vị cm
 * @property double $delivery_note_dimension_w chiều rộng của cả gói , đơn vị cm
 * @property double $delivery_note_dimension_h chiều cao của cả gói , đơn vị cm
 * @property string $seller_shipped
 * @property string $stock_in_us
 * @property string $stock_out_us
 * @property string $stock_in_local
 * @property string $lost
 * @property string $current_status
 * @property int $warehouse_id id kho nhận
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property int $remove
 * @property string $version version 4.0
 * @property int $shipment_id
 * @property int $customer_id
 * @property int $receiver_address_id id địa chỉ nhận của khách
 * @property string $receiver_name
 * @property string $receiver_email
 * @property string $receiver_phone
 * @property string $receiver_address
 * @property int $receiver_country_id
 * @property string $receiver_country_name
 * @property int $receiver_province_id
 * @property string $receiver_province_name
 * @property int $receiver_district_id
 * @property string $receiver_district_name
 * @property string $receiver_post_code
 * @property int $insurance 0: auto, 1: insurance, 2: unInsurance
 * @property int $pack_wood 0: unInsurance, 1: insurance
 *
 * @property Warehouse $warehouse
 */
class DeliveryNote extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%delivery_note}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_ids', 'tracking_reference_1', 'tracking_reference_2', 'manifest_code'], 'string'],
            [['delivery_note_weight', 'delivery_note_change_weight', 'delivery_note_dimension_l', 'delivery_note_dimension_w', 'delivery_note_dimension_h'], 'number'],
            [['seller_shipped', 'stock_in_us', 'stock_out_us', 'stock_in_local', 'lost', 'warehouse_id', 'created_at', 'updated_at', 'remove', 'shipment_id', 'customer_id', 'receiver_address_id', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'insurance', 'pack_wood'], 'integer'],
            [['delivery_note_code'], 'string', 'max' => 32],
            [['tracking_seller', 'version', 'receiver_name', 'receiver_email', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code'], 'string', 'max' => 255],
            [['current_status'], 'string', 'max' => 100],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'delivery_note_code' => Yii::t('db', 'Delivery Note Code'),
            'tracking_seller' => Yii::t('db', 'Tracking Seller'),
            'order_ids' => Yii::t('db', 'Order Ids'),
            'tracking_reference_1' => Yii::t('db', 'Tracking Reference 1'),
            'tracking_reference_2' => Yii::t('db', 'Tracking Reference 2'),
            'manifest_code' => Yii::t('db', 'Manifest Code'),
            'delivery_note_weight' => Yii::t('db', 'Delivery Note Weight'),
            'delivery_note_change_weight' => Yii::t('db', 'Delivery Note Change Weight'),
            'delivery_note_dimension_l' => Yii::t('db', 'Delivery Note Dimension L'),
            'delivery_note_dimension_w' => Yii::t('db', 'Delivery Note Dimension W'),
            'delivery_note_dimension_h' => Yii::t('db', 'Delivery Note Dimension H'),
            'seller_shipped' => Yii::t('db', 'Seller Shipped'),
            'stock_in_us' => Yii::t('db', 'Stock In Us'),
            'stock_out_us' => Yii::t('db', 'Stock Out Us'),
            'stock_in_local' => Yii::t('db', 'Stock In Local'),
            'lost' => Yii::t('db', 'Lost'),
            'current_status' => Yii::t('db', 'Current Status'),
            'warehouse_id' => Yii::t('db', 'Warehouse ID'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
            'shipment_id' => Yii::t('db', 'Shipment ID'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'receiver_address_id' => Yii::t('db', 'Receiver Address ID'),
            'receiver_name' => Yii::t('db', 'Receiver Name'),
            'receiver_email' => Yii::t('db', 'Receiver Email'),
            'receiver_phone' => Yii::t('db', 'Receiver Phone'),
            'receiver_address' => Yii::t('db', 'Receiver Address'),
            'receiver_country_id' => Yii::t('db', 'Receiver Country ID'),
            'receiver_country_name' => Yii::t('db', 'Receiver Country Name'),
            'receiver_province_id' => Yii::t('db', 'Receiver Province ID'),
            'receiver_province_name' => Yii::t('db', 'Receiver Province Name'),
            'receiver_district_id' => Yii::t('db', 'Receiver District ID'),
            'receiver_district_name' => Yii::t('db', 'Receiver District Name'),
            'receiver_post_code' => Yii::t('db', 'Receiver Post Code'),
            'insurance' => Yii::t('db', 'Insurance'),
            'pack_wood' => Yii::t('db', 'Pack Wood'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }
}
