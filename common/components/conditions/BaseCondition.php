<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 16:04
 */

namespace common\components\conditions;


use common\components\AdditionalFeeInterface;
use common\models\StoreAdditionalFee;

abstract class BaseCondition extends \yii\base\BaseObject
{

    /**
     * @var string name of the condition
     */
    public $name;
    /**
     * @var int UNIX timestamp representing the rule creation time
     */
    public $createdAt;
    /**
     * @var int UNIX timestamp representing the rule updating time
     */
    public $updatedAt;

    /**
     * Executes the condition.
     * @param integer $value raw value
     * @param AdditionalFeeInterface $additionalFee
     * @param $storeAdditionalFee $config
     * @return integer
     */
    abstract public function execute($value, $additionalFee, $storeAdditionalFee);
}