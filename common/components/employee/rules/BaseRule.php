<?php


namespace common\components\employee\rules;


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
     * @return User
     */
    abstract function getActiveSupporter();
}