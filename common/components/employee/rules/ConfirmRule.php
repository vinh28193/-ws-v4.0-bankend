<?php

namespace common\components\employee\rules;

use common\models\Order;
use yii\db\Expression;
use yii\db\Query;

class ConfirmRule extends BaseRule
{

    /**
     * @return array
     */
    public function getActiveSupporter()
    {
        return [];
    }

    private $_confirmPercent = [];

    public function getConfirmPercent()
    {
        if (empty($this->_confirmPercent)) {
            $percents = [];
            foreach ($this->employee->getSupporters() as $id => $supporter) {

            }
        }
        return $this->_confirmPercent;
    }

    public function loadConfirmPercentBySupporter($ids = [])
    {
        $q = new Query();
        $q->from(['o' => Order::tableName()]);
        $q->select([
            'support' => new Expression('`o`.`support_id`'),
            'total' => new Expression('COUNT(`o`.`id`)'),
            'supported' => new Expression('COUNT(IF(`o`.`supported` is not null ,1,null))')
        ]);
    }
}