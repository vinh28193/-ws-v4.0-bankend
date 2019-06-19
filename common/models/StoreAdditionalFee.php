<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 09:24
 */

namespace common\models;

use common\boxme\InternationalShippingCalculator;
use Yii;
use yii\helpers\Json;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;
use common\models\db\StoreAdditionalFee as DbStoreAdditionalFee;

class StoreAdditionalFee extends DbStoreAdditionalFee
{

}