<?php

namespace common\fixtures;
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-01
 * Time: 11:13
 */

use yii\test\ActiveFixture;

class SystemExchangeRateFixture  extends ActiveFixture
{
    public $tableName = 'system_exchange_rate';
    public $dataFile = '@common/fixtures/data/data_fixed/system_exchange_rate.php';
}