<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shipment".
 *
 * @property int $id
 * @property string $shipment_code mã phiếu giao, BM_CODE
 * @property string $warehouse_tags 1 list mã thẻ kho Weshop
 * @property double $total_weight Tổng cân nặng của các món hàng
 * @property int $warehouse_send_id id kho gửi đi
 * @property int $customer_id id của customer
 * @property string $receiver_email
 * @property string $receiver_name
 * @property string $receiver_phone
 * @property string $receiver_address
 * @property int $receiver_country_id
 * @property string $receiver_country_name
 * @property int $receiver_province_id
 * @property string $receiver_province_name
 * @property int $receiver_district_id
 * @property string $receiver_district_name
 * @property string $receiver_post_code
 * @property int $receiver_address_id id address của người nhận trong bảng address
 * @property string $note_by_customer Ghi chú của customer
 * @property string $note Ghi chú cho đơn hàng
 * @property string $shipment_status trạng thái shipment
 * @property string $total_shipping_fee phí ship
 * @property string $total_price Tổng giá trị shipment
 * @property string $total_cod Tổng tiền thu cod
 * @property int $total_quantity Tổng số lượng
 * @property int $is_hold đánh dấu hàng hold, 0 là không hold, 1 là hold
 * @property int $courier_code mã hãng vận chuyển
 * @property string $courier_logo logo hãng vận chuyển
 * @property string $courier_estimate_time thời gian ước tính của hãng vận chuyển
 * @property string $list_old_shipment_code danh sách mã shipment cũ đã bị cancel
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property string $version version 4.0
 */
class Shipment extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warehouse_tags', 'note_by_customer', 'note', 'courier_logo', 'courier_estimate_time', 'list_old_shipment_code'], 'string'],
            [['total_weight', 'total_shipping_fee', 'total_price', 'total_cod'], 'number'],
            [['warehouse_send_id', 'customer_id', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'total_quantity', 'is_hold', 'courier_code', 'created_at', 'updated_at'], 'integer'],
            [['shipment_code'], 'string', 'max' => 32],
            [['receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'shipment_status', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shipment_code' => 'Shipment Code',
            'warehouse_tags' => 'Warehouse Tags',
            'total_weight' => 'Total Weight',
            'warehouse_send_id' => 'Warehouse Send ID',
            'customer_id' => 'Customer ID',
            'receiver_email' => 'Receiver Email',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => 'Receiver Phone',
            'receiver_address' => 'Receiver Address',
            'receiver_country_id' => 'Receiver Country ID',
            'receiver_country_name' => 'Receiver Country Name',
            'receiver_province_id' => 'Receiver Province ID',
            'receiver_province_name' => 'Receiver Province Name',
            'receiver_district_id' => 'Receiver District ID',
            'receiver_district_name' => 'Receiver District Name',
            'receiver_post_code' => 'Receiver Post Code',
            'receiver_address_id' => 'Receiver Address ID',
            'note_by_customer' => 'Note By Customer',
            'note' => 'Note',
            'shipment_status' => 'Shipment Status',
            'total_shipping_fee' => 'Total Shipping Fee',
            'total_price' => 'Total Price',
            'total_cod' => 'Total Cod',
            'total_quantity' => 'Total Quantity',
            'is_hold' => 'Is Hold',
            'courier_code' => 'Courier Code',
            'courier_logo' => 'Courier Logo',
            'courier_estimate_time' => 'Courier Estimate Time',
            'list_old_shipment_code' => 'List Old Shipment Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
        ];
    }
}
