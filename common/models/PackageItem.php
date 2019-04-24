<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 13:24
 */

namespace common\models;


use Yii;
use common\models\db\PackageItem as DbPackageItem;
use common\models\queries\PackageItemQuery;
/**
 * Class PackageItem
 * @package common\models
 *
 * @property Order $order
 * @property  Package $package
 * @property Product $product
 *
 */

class PackageItem extends DbPackageItem
{
    public function rules()
    {
        return [
            [['package_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     * @return PackageItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(PackageItemQuery::className(), [get_called_class()]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::className(), ['id' => 'shipment_id']);
    }
}
