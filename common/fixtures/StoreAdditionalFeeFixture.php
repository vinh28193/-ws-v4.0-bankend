<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 09:27
 */

namespace common\fixtures;


class StoreAdditionalFeeFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'common\models\StoreAdditionalFee';
    public $dataFile = '@common/fixtures/data/data_fixed/store_additional_fee.php';
}
