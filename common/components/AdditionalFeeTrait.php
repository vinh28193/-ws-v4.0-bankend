<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 17:58
 */

namespace common\components;

trait AdditionalFeeTrait
{
    public $additionalFees;

    public function getAdditionalFees($isLoad = false)
    {
        if ($this->additionalFees === null || $isLoad) {
            $this->additionalFees = new AdditionalFeeCollection();
//            if ($isLoad) {
                $this->additionalFees->loadFormOwner($this);
//            }
        }
        return $this->additionalFees;
    }
}
