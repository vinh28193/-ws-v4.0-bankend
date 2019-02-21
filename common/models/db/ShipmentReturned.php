<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shipment_returned".
 *
 * @property int $id
 * @property int $shipment_code mã phiếu giao, BM_CODE
 * @property int $warehouse_send_id id kho gửi đi
 * @property string $warehouse_tags 1 list mã thẻ kho Weshop
 * @property int $customer_id id của customer
 * @property string $shipment_status trạng thái shipment
 * @property double $total_weight Tổng cân nặng của các món hàng
 * @property string $total_shipping_fee phí ship
 * @property string $total_price Tổng giá trị shipment
 * @property string $total_cod Tổng tiền thu cod
 * @property int $total_quantity Tổng số lượng
 * @property int $courier_code mã hãng vận chuyển
 * @property string $courier_logo logo hãng vận chuyển
 * @property string $courier_estimate_time thời gian ước tính của hãng vận chuyển
 * @property int $shipment_id
 * @property string $created_time thời gian tạo
 * @property string $updated_time thời gian cập nhật
 *
 * @property Customer $customer
 * @property Shipment $shipment
 * @property Warehouse $warehouseSend
 */
class ShipmentReturned extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment_returned';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipment_code', 'warehouse_send_id', 'customer_id', 'total_quantity', 'courier_code', 'shipment_id', 'created_time', 'updated_time'], 'integer'],
            [['warehouse_tags', 'courier_logo', 'courier_estimate_time'], 'string'],
            [['total_weight', 'total_shipping_fee', 'total_price', 'total_cod'], 'number'],
            [['shipment_status'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::className(), 'targetAttribute' => ['shipment_id' => 'id']],
            [['warehouse_send_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_send_id' => 'id']],
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
            'warehouse_send_id' => 'Warehouse Send ID',
            'warehouse_tags' => 'Warehouse Tags',
            'customer_id' => 'Customer ID',
            'shipment_status' => 'Shipment Status',
            'total_weight' => 'Total Weight',
            'total_shipping_fee' => 'Total Shipping Fee',
            'total_price' => 'Total Price',
            'total_cod' => 'Total Cod',
            'total_quantity' => 'Total Quantity',
            'courier_code' => 'Courier Code',
            'courier_logo' => 'Courier Logo',
            'courier_estimate_time' => 'Courier Estimate Time',
            'shipment_id' => 'Shipment ID',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
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
    public function getShipment()
    {
        return $this->hasOne(Shipment::className(), ['id' => 'shipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouseSend()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_send_id']);
    }
}
