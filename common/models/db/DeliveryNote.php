<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "delivery_note".
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
 */
class DeliveryNote extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_ids', 'tracking_reference_1', 'tracking_reference_2', 'manifest_code'], 'string'],
            [['delivery_note_weight', 'delivery_note_change_weight', 'delivery_note_dimension_l', 'delivery_note_dimension_w', 'delivery_note_dimension_h'], 'number'],
            [['seller_shipped', 'stock_in_us', 'stock_out_us', 'stock_in_local', 'lost', 'warehouse_id', 'created_at', 'updated_at', 'remove', 'shipment_id', 'customer_id', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'insurance', 'pack_wood'], 'integer'],
            [['delivery_note_code'], 'string', 'max' => 32],
            [['tracking_seller', 'version', 'receiver_name', 'receiver_email', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code'], 'string', 'max' => 255],
            [['current_status'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_note_code' => 'Delivery Note Code',
            'tracking_seller' => 'Tracking Seller',
            'order_ids' => 'Order Ids',
            'tracking_reference_1' => 'Tracking Reference 1',
            'tracking_reference_2' => 'Tracking Reference 2',
            'manifest_code' => 'Manifest Code',
            'delivery_note_weight' => 'Delivery Note Weight',
            'delivery_note_change_weight' => 'Delivery Note Change Weight',
            'delivery_note_dimension_l' => 'Delivery Note Dimension L',
            'delivery_note_dimension_w' => 'Delivery Note Dimension W',
            'delivery_note_dimension_h' => 'Delivery Note Dimension H',
            'seller_shipped' => 'Seller Shipped',
            'stock_in_us' => 'Stock In Us',
            'stock_out_us' => 'Stock Out Us',
            'stock_in_local' => 'Stock In Local',
            'lost' => 'Lost',
            'current_status' => 'Current Status',
            'warehouse_id' => 'Warehouse ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
            'shipment_id' => 'Shipment ID',
            'customer_id' => 'Customer ID',
            'receiver_name' => 'Receiver Name',
            'receiver_email' => 'Receiver Email',
            'receiver_phone' => 'Receiver Phone',
            'receiver_address' => 'Receiver Address',
            'receiver_country_id' => 'Receiver Country ID',
            'receiver_country_name' => 'Receiver Country Name',
            'receiver_province_id' => 'Receiver Province ID',
            'receiver_province_name' => 'Receiver Province Name',
            'receiver_district_id' => 'Receiver District ID',
            'receiver_district_name' => 'Receiver District Name',
            'receiver_post_code' => 'Receiver Post Code',
            'insurance' => 'Insurance',
            'pack_wood' => 'Pack Wood',
        ];
    }
}
