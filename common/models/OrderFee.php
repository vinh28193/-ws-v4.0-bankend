<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 10:32
 */

namespace common\models;

use common\models\db\OrderFee as DbOrderFee;
use common\components\StoreAdditionalFeeRegisterTrait;

class OrderFee extends DbOrderFee
{
    use StoreAdditionalFeeRegisterTrait;

    public function getTotalAdditionalFee($names = null)
    {
        if ($names === null) {
            $names = array_keys($this->storeAdditionalFee);
        }
        if (!is_array($names)) {
            $names = [$names];
        }
        $totalAdditionFee = 0;
        foreach ($names as $name) {
            if ($this->canGetProperty($name)) {
                $totalAdditionFee += $this->$name;
            } else {
                Yii::warning("failed when get property $name in " . get_class($this));
            }
        }
    }

    public function setTotalAdditionalFee($additionalFees){

        if(!is_array($additionalFees)){
            throw new \yii\base\InvalidArgumentException(get_class($this)."setTotalAdditionalFee must be requited array");
        }
        foreach ($additionalFees as $name => $value){
            if ($this->canSetProperty($name)) {
                $this->$name = $value;
            } else {
                Yii::warning("failed when set property $name value $value in " . get_class($this));
            }
        }
    }
}