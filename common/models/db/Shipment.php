<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%shipment}}".
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
 * @property int $is_insurance đánh dấu bảo hiểm
 * @property string $courier_code mã hãng vận chuyển
 * @property string $courier_logo mã hãng vận chuyển
 * @property string $courier_estimate_time thời gian ước tính của hãng vận chuyển
 * @property string $list_old_shipment_code danh sách mã shipment cũ đã bị cancel
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property string $version version 4.0
 * @property int $active
 * @property int $shipment_send_at
 *
 * @property Customer $customer
 * @property Address $receiverAddress
 * @property SystemCountry $receiverCountry
 * @property SystemDistrict $receiverDistrict
 * @property SystemStateProvince $receiverProvince
 * @property Warehouse $warehouseSend
 * @property ShipmentReturned[] $shipmentReturneds
 */
class Shipment extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shipment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warehouse_tags', 'note_by_customer', 'note', 'courier_logo', 'courier_estimate_time', 'list_old_shipment_code'], 'string'],
            [['total_weight', 'total_shipping_fee', 'total_price', 'total_cod'], 'number'],
            [['warehouse_send_id', 'customer_id', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'total_quantity', 'is_hold', 'is_insurance', 'created_at', 'updated_at', 'active', 'shipment_send_at'], 'integer'],
            [['shipment_code', 'courier_code'], 'string', 'max' => 32],
            [['receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'shipment_status', 'version'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['receiver_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['receiver_address_id' => 'id']],
            [['receiver_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['receiver_country_id' => 'id']],
            [['receiver_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['receiver_district_id' => 'id']],
            [['receiver_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['receiver_province_id' => 'id']],
            [['warehouse_send_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_send_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'shipment_code' => Yii::t('db', 'Shipment Code'),
            'warehouse_tags' => Yii::t('db', 'Warehouse Tags'),
            'total_weight' => Yii::t('db', 'Total Weight'),
            'warehouse_send_id' => Yii::t('db', 'Warehouse Send ID'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'receiver_email' => Yii::t('db', 'Receiver Email'),
            'receiver_name' => Yii::t('db', 'Receiver Name'),
            'receiver_phone' => Yii::t('db', 'Receiver Phone'),
            'receiver_address' => Yii::t('db', 'Receiver Address'),
            'receiver_country_id' => Yii::t('db', 'Receiver Country ID'),
            'receiver_country_name' => Yii::t('db', 'Receiver Country Name'),
            'receiver_province_id' => Yii::t('db', 'Receiver Province ID'),
            'receiver_province_name' => Yii::t('db', 'Receiver Province Name'),
            'receiver_district_id' => Yii::t('db', 'Receiver District ID'),
            'receiver_district_name' => Yii::t('db', 'Receiver District Name'),
            'receiver_post_code' => Yii::t('db', 'Receiver Post Code'),
            'receiver_address_id' => Yii::t('db', 'Receiver Address ID'),
            'note_by_customer' => Yii::t('db', 'Note By Customer'),
            'note' => Yii::t('db', 'Note'),
            'shipment_status' => Yii::t('db', 'Shipment Status'),
            'total_shipping_fee' => Yii::t('db', 'Total Shipping Fee'),
            'total_price' => Yii::t('db', 'Total Price'),
            'total_cod' => Yii::t('db', 'Total Cod'),
            'total_quantity' => Yii::t('db', 'Total Quantity'),
            'is_hold' => Yii::t('db', 'Is Hold'),
            'is_insurance' => Yii::t('db', 'Is Insurance'),
            'courier_code' => Yii::t('db', 'Courier Code'),
            'courier_logo' => Yii::t('db', 'Courier Logo'),
            'courier_estimate_time' => Yii::t('db', 'Courier Estimate Time'),
            'list_old_shipment_code' => Yii::t('db', 'List Old Shipment Code'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'version' => Yii::t('db', 'Version'),
            'active' => Yii::t('db', 'Active'),
            'shipment_send_at' => Yii::t('db', 'Shipment Send At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'receiver_address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'receiver_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'receiver_district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'receiver_province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouseSend()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_send_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipmentReturneds()
    {
        return $this->hasMany(ShipmentReturned::className(), ['shipment_id' => 'id']);
    }
}
