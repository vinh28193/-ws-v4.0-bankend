<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 09:24
 */

namespace common\models;

use common\models\db\StoreAdditionalFee as DbStoreAdditionalFee;

class StoreAdditionalFee extends DbStoreAdditionalFee
{
    use \common\components\conditions\ConditionTrait;
}