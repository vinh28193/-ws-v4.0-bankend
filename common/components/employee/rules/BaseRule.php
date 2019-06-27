<?php


namespace common\components\employee\rules;

use Yii;
use common\models\User;
use yii\base\BaseObject;
use common\components\employee\Employee;

abstract class BaseRule extends BaseObject
{

    /**
     * @var Employee
     */
    public $employee;

    /**
     * @return User
     */
    abstract function getActiveSupporter();
}