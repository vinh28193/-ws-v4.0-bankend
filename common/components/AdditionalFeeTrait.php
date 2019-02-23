<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 17:58
 */

namespace common\components;

use Yii;

trait AdditionalFeeTrait
{
    /**
     * @param null $names
     * @param array $except
     * @return array
     */
    public function getAdditionalFees($names = null, $except = [])
    {
        $values = [];
        $storeAdditionalFee = array_keys($this->storeAdditionalFee);
        if ($names === null) {
            $names = $storeAdditionalFee;
        }
        if (is_string($names)) {
            $names = [$names];
        }
        foreach ($names as $name) {
            if (!in_array($name, $storeAdditionalFee)) {
                Yii::warning("failed when get unknown additional fee '$name'");
                continue;
            }
            $values[$name] = $this->$name;
        }

        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    public function setAdditionalFees($values, $withCondition = false)
    {
        if (is_array($values)) {
            $fees = $this->storeAdditionalFee;
            foreach ($values as $name => $value) {
                if ($this->hasProperty($name, true) && isset($fees[$name]) && ($storeAdditionalFee = $fees[$name]) !== null && $storeAdditionalFee instanceof StoreAdditionalFee) {
                    if ($withCondition && $this instanceof AdditionalFeeInterface && $storeAdditionalFee->hasMethod('executeCondition')) {
                        $value = $storeAdditionalFee->executeCondition($value, $this);
                    }
                    $this->$name = $value;
                } else {
                    Yii::warning("failed when set unknown additional fee '$name'");
                }
            }
        }
    }

    public function getTotalAdditionFees($names = null, $except = [])
    {
        $totalFees = 0;
        foreach ((array)$this->getAdditionalFees($names, $except) as $name => $value) {
            $totalFees += $value;
        }
        return $totalFees;

    }
}