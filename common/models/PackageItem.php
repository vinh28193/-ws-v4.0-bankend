<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 13:24
 */

namespace common\models;


use common\models\db\Product;

/**
 * Class PackageItem
 * @package common\models
 */

class PackageItem extends \common\models\db\PackageItem
{
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getProduct()
//    {
//        return $this->hasOne(Product::className(), ['order_id' => 'order_id'])->where(['sku' => $this->sku]);
//    }
}