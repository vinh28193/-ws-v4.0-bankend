<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 10:41
 */

namespace common\models;

use Yii;
use common\models\queries\ShipmentQuery;
use common\models\db\Shipment as DbShipment;
/**
 * Class Shipment
 * @package common\models
 * @property Package[] $packages
 * @property PackageItem[] $packageItems
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
    const STATUS_WAITING = 'WAITING';
    const STATUS_CREATED = 'CREATED';
    const STATUS_FAILED = 'FAILED';
    // callback status
    const STATUS_PICKING = 'PICKING';

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
    public function getPackageItems()
    {
        return $this->hasMany(PackageItem::className(), ['shipment_id' => 'id']);
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
        foreach ($this->packageItems as $packageItem){
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
