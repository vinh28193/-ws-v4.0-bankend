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
                'class' => \common\behaviors\OrderFeeBehavior::className()
            ]
        ]);
    }

    /**
     * @return \common\components\StoreManager|void
     */
    public function getStoreManager(){

    }

    /**
     * @return string
     */
    public function getItemType(){
            return '';
    }

    /**
     * @return integer
     */
    public function getTotalOriginPrice(){

    }

    /**
     * @return mixed
     */
    public function getCustomCategory(){

    }

    /**
     * @return integer
     */
    public function getShippingWeight(){

    }
    /**
     * @return integer
     */
    public function getShippingQuantity(){

    }
    /**
     * @return boolean
     */
    public function getIsForWholeSale(){

    }
    /**
     * @return integer
     */
    public function getExchangeRate(){

    }

}