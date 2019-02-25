<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 16:07
 */

namespace common\tests\_support;


class StoreAdditionalFee extends \yii\base\BaseObject
{
    use \common\components\conditions\ConditionTrait;

    public $name;
    public $condition_name;
    public $condition_data;
}