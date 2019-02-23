<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 11:21
 */

namespace common\models;

use common\components\AdditionalFeeInterface;
use \common\models\db\Order as DbOrder;

class Order extends DbOrder implements AdditionalFeeInterface
{

    use \common\components\StoreAdditionalFeeRegisterTrait;
    use \common\components\AdditionalFeeTrait;

    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'orderFee' => [
                'class' => \common\behaviors\AdditionalFeeBehavior::className()
            ]
        ]);
    }

    /**
     * @return \common\components\StoreManager|void
     */
    public function getStoreManager(){
        return \Yii::$app->storeManager;
    }

    public function getItemType()
    {
        return 'test';
    }

    public function getTotalOriginPrice()
    {
        return 12344.99;
    }

    public function getCustomCategory()
    {
        $std = new \stdClass();
        $std->interShippingB = 123;
        return $std;
    }

    public function getIsForWholeSale()
    {
        return false;
    }

    public function getShippingWeight()
    {
        return 5;
    }

    public function getShippingQuantity()
    {
        return 2;
    }

    public function getExchangeRate()
    {
        return 32000;
    }

}