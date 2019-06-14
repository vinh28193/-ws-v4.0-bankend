<?php


namespace common\boxme;


use Yii;
use yii\base\BaseObject;
use common\components\AdditionalFeeInterface;

class InternationalShippingCalculator extends BaseObject
{

    public function trace(AdditionalFeeInterface $additional){
        $feeValue = rand(1,99);
        return $feeValue;
    }
}