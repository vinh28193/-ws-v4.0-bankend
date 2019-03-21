<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 20:53
 */

namespace common\fixtures;


class TrackingCodeFixture extends \yii\test\ActiveFixture
{

    public $modelClass = 'common\models\TrackingCode';

    public $dataFile = '@common/fixtures/data/data_fixed/tracking_code.php';
}