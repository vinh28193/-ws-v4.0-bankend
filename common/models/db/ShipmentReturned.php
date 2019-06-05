<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%shipment_returned}}".
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
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property string $version version 4.0
 *
 * @property Customer $customer
 * @property Shipment $shipment
 * @property Warehouse $warehouseSend
 */
class ShipmentReturned extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shipment_returned}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipment_code', 'warehouse_send_id', 'customer_id', 'total_quantity', 'courier_code', 'shipment_id', 'created_at', 'updated_at'], 'integer'],
            [['warehouse_tags', 'courier_logo', 'courier_estimate_time'], 'string'],
            [['total_weight', 'total_shipping_fee', 'total_price', 'total_cod'], 'number'],
            [['shipment_status', 'version'], 'string', 'max' => 255],
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
            'id' => Yii::t('db', 'ID'),
            'shipment_code' => Yii::t('db', 'Shipment Code'),
            'warehouse_send_id' => Yii::t('db', 'Warehouse Send ID'),
            'warehouse_tags' => Yii::t('db', 'Warehouse Tags'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'shipment_status' => Yii::t('db', 'Shipment Status'),
            'total_weight' => Yii::t('db', 'Total Weight'),
            'total_shipping_fee' => Yii::t('db', 'Total Shipping Fee'),
            'total_price' => Yii::t('db', 'Total Price'),
            'total_cod' => Yii::t('db', 'Total Cod'),
            'total_quantity' => Yii::t('db', 'Total Quantity'),
            'courier_code' => Yii::t('db', 'Courier Code'),
            'courier_logo' => Yii::t('db', 'Courier Logo'),
            'courier_estimate_time' => Yii::t('db', 'Courier Estimate Time'),
            'shipment_id' => Yii::t('db', 'Shipment ID'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'version' => Yii::t('db', 'Version'),
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
