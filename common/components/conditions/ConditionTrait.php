<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 10:40
 */

namespace common\components\conditions;


use common\components\AdditionalFeeInterface;

trait ConditionTrait
{

    /**
     * @return BaseCondition|null|mixed
     */
    public function getCondition()
    {
        $data = $this->condition_data;
        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        return unserialize($data);
    }

    /**
     * @param BaseCondition $condition
     */
    public function setCondition(BaseCondition $condition)
    {
        $time = time();
        if ($condition->createdAt === null) {
            $condition->createdAt = $time;
        }
        if ($condition->updatedAt === null) {
            $condition->updatedAt = $time;
        }
        $this->updateAttributes([
            'condition_name' => $condition->name,
            'condition_data' => serialize($condition),
        ]);
    }

    public function executeCondition($value, AdditionalFeeInterface $additionalFee)
    {
        if ($this->condition_name === null) {
            return $value;
        }

        $condition = $condition = $this->getCondition();
        if ($condition instanceof BaseCondition) {
            return $condition->execute($value, $additionalFee, $this);
        }
        return $value;

    }
}