<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 13:07
 */

namespace common\models;

use common\models\db\Package as DbPackage;
use common\models\queries\PackageQuery;
use Yii;

class Package extends DbPackage
{

    /**
     * @inheritdoc
     * @return PackageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(PackageQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageItems()
    {
        return $this->hasMany(PackageItem::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinPackageItems()
    {
        return $this->getPackageItems()->select(['id', 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    public function timestampFields()
    {
        return array_merge(parent::timestampFields(), [
            'seller_shipped',
            'stock_in_us',
            'stock_out_us',
            'stock_in_local',
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        return parent::fields();
    }

}