<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 10:41
 */

namespace common\models;

use common\models\Package;
use Yii;
use common\models\queries\ShipmentQuery;
use common\models\db\Shipment as DbShipment;
/**
 * Class Shipment
 * @package common\models
 * @property Package[] $packages
 * @property Package[] $packageItems
 * @property Customer $customer
 * @property Address $receiverAddress
 * @property SystemCountry $receiverCountry
 * @property SystemDistrict $receiverDistrict
 * @property SystemStateProvince $receiverProvince
 * @property Warehouse $warehouseSend
 */

class Shipment extends DbShipment
{
    // operation status
    const STATUS_NEW = 'NEW';
    const STATUS_WAITING = 'SHIPMENT_WAITING';
    const STATUS_CREATED = 'SHIPMENT_CREATED';
    const STATUS_FAILED = 'SHIPMENT_FAILED';
    // callback status
    const STATUS_APPROVED = 'SHIPMENT_APPROVED'; // 200: đơn đã duyêt
    const STATUS_PICKING = 'SHIPMENT_PICKING'; //300: Hãng vận chuyển đã lấy hàng
    const STATUS_SHIPPED = 'SHIPMENT_SHIPPED';
    const STATUS_DELIVERING = 'SHIPMENT_DELIVERING'; // 304: đơn hàng đang vận chuyển
    const STATUS_DELIVERED = 'SHIPMENT_DELIVERED'; //800
    const STATUS_RETURNING = 'SHIPMENT_RETURNING'; // 400,410,420,430,500,510,511,520
    const STATUS_RETURNED = 'SHIPMENT_RETURNED'; // 600
    const STATUS_CANCELED = 'SHIPMENT_CANCELED'; //700,701,702,703,705
    // common status
    const STATUS_DESTROY = 'SHIPMENT_DESTROY'; // callback 810 or operation destroy

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        $paren = parent::rules();
        $child = [
            [['warehouse_send_id', 'customer_id', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id'], 'required'],
        ];
        return array_merge($paren,$child);
    }

    /**
     * @inheritdoc
     * @return ShipmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(ShipmentQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['shipment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryNotes()
    {
        return $this->hasMany(DeliveryNote::className(), ['shipment_id' => 'id']);
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
     *
     */
    public function reCalculateTotal(){
        $totalQuantity = 0;
        $totalWeight = 0;
        $totalCod = 0;
        $totalPrice = 0;
        foreach ($this->packages as $packageItem){
            $totalQuantity += $packageItem->quantity;
            $totalWeight += $packageItem->weight;
            $totalCod += $packageItem->cod;
            $totalPrice += $packageItem->price;
        }
        $this->updateAttributes([
            'total_quantity' => $totalQuantity,
            'total_weight' => $totalWeight,
            'total_cod' => $totalCod,
            'total_price' => $totalPrice
        ]);
    }
}
