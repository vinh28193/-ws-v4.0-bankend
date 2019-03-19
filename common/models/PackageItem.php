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
 */

class PackageItem extends DbPackageItem
{
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['order_id' => 'id'])->via('order');
    }
}