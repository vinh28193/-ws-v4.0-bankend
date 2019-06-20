<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 17:58
 */

namespace common\additional;

trait AdditionalFeeTrait
{
    public $additionalFees;

    public function getAdditionalFees()
    {
        if ($this->additionalFees === null) {
            $this->additionalFees = new AdditionalFeeCollection();
        }
        return $this->additionalFees;
    }
}
