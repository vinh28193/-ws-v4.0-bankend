<?php


namespace common\tests\stubs;

use Yii;
use stdClass;
use common\components\AdditionalFeeInterface;
use common\components\StoreAdditionalFeeRegisterTrait;
use common\components\AdditionalFeeTrait;
use yii\base\BaseObject;

class AdditionalFeeObject extends BaseObject
{
    use AdditionalFeeTrait;
}