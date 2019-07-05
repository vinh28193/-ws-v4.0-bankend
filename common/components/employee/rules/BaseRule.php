<?php


namespace common\components\employee\rules;


use common\models\Order;
use Yii;
use common\models\User;
use yii\base\BaseObject;
use common\components\employee\Employee;
use common\components\GetUserIdentityTrait;

abstract class BaseRule extends BaseObject
{
    use GetUserIdentityTrait;
    /**
     * @var Employee
     */
    public $employee;

    /**
     * get active supporter pass with current rule
     * @param Order[] $orders
     * @return User[]|array
     */
    abstract function getActiveSupporters($orders);

    /**
     * do not use with [[getActiveSupporter]] together
     * @param string|int $spId
     * @param Order[] $orders
     * @return bool
     */
    abstract function markAssigned($spId, $orders);
}