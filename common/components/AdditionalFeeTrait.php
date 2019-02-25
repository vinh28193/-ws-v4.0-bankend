<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 17:58
 */

namespace common\components;

use common\models\StoreAdditionalFee;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

trait AdditionalFeeTrait
{

    public $additionalFeeModel = 'common\models\OrderFee';
    /**
     * @var array
     */
    protected $_additionalFees = [];

    /**
     * @param null $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAdditionalFees($names = null)
    {
        if (empty($this->_additionalFees)) {
            if ($names === null) {
                $names = array_keys($this->storeAdditionalFee);;
            }
            /** @var  $class \yii\db\ActiveRecord */
            $class = $this->additionalFeeModel;
            $query = new Query();
            $query->select(['c.id', 'c.type_fee', 'c.amount', 'c.amount_local', 'c.currency', 'c.discount_amount']);
            $query->from(['c' => $class::tableName()]);
            $query->where(['and', ['c.order_id' => $this->id], ['in', 'c.type_fee', $names]]);
            $additionalFees = $query->all($class::getDb());
            $additionalFees = ArrayHelper::index($additionalFees, null, 'type_fee');
            $this->_additionalFees = $additionalFees;
        }
        return $this->_additionalFees;
    }

    /**
     * @param $values
     * @param bool $withCondition
     * @param bool $ensureReadOnly
     */
    public function setAdditionalFees($values, $withCondition = false, $ensureReadOnly = true)
    {
        if (is_array($values)) {
            $fees = $this->storeAdditionalFee;
            foreach ($values as $name => $value) {
                $localValue = $value;
                if (isset($fees[$name]) && ($storeAdditionalFee = $fees[$name]) !== null && $storeAdditionalFee instanceof StoreAdditionalFee) {
                    if ($withCondition && $this instanceof AdditionalFeeInterface && $storeAdditionalFee->hasMethod('executeCondition')) {
                        $value = $storeAdditionalFee->executeCondition($value, $this);
                        if ($storeAdditionalFee->is_convert) {
                            $localValue = $value * $this->getExchangeRate();
                        }
                    }
                    if($storeAdditionalFee->is_read_only){
                        Yii::warning("can not read only additional fee '$name'");
                        continue;
                    }
                    $additionalFee = [
                        'type_fee' => $name,
                        'amount' => $value,
                        'amount_local' => $localValue,
                        'currency' => $storeAdditionalFee->currency,
                        'discount_amount' => $this->hasAttribute('discount_amount') ? $this->discount_amount : 0,
                    ];
                    $owner = "total_{$name}_local";
                    if ($this->canSetProperty($owner)) {
                        $this->$owner = $localValue;
                    }
                    $this->_additionalFees[$name] = [$additionalFee];
                } else {
                    Yii::warning("failed when set unknown additional fee '$name'");
                }
            }
            if($ensureReadOnly){
                $breaks = array_keys($values);
                foreach ($fees as $name => $storeAdditionalFee){
                    /** @var $storeAdditionalFee StoreAdditionalFee */
                    if (in_array($name,$breaks)){
                        continue;
                    }
                    $value = 0;
                    if ($withCondition && $this instanceof AdditionalFeeInterface && $storeAdditionalFee->hasMethod('executeCondition')) {
                        $value = $storeAdditionalFee->executeCondition($value, $this);
                    }
                    $localValue = $value;
                    if ($storeAdditionalFee->is_convert) {
                        $localValue = $value * $this->getExchangeRate();
                    }
                    $additionalFee = [
                        'type_fee' => $name,
                        'amount' => $value,
                        'local_amount' => $localValue,
                        'currency' => $storeAdditionalFee->currency,
                        'discount_amount' => $this->hasAttribute('discount_amount') ? $this->discount_amount : 0,
                    ];
                    $owner = "total_{$name}_local";
                    if ($this->canSetProperty($owner)) {
                        $this->$owner = $localValue;
                    }
                    $this->_additionalFees[$name] = [$additionalFee];
                }
            }
        }
    }

    /**
     * @param null $names
     * @return array
     */
    public function getTotalAdditionFees($names = null)
    {
        $totalFees = 0;
        $totalLocalFees = 0;
        foreach ((array)$this->getAdditionalFees($names) as $arrays) {
            foreach ($arrays as $array) {
                $totalFees += isset($array['amount']) ? $array['amount'] : 0;
                $totalLocalFees += isset($array['amount_local']) ? $array['amount_local'] : 0;
            }
        }
        return [$totalFees, $totalLocalFees];

    }
}