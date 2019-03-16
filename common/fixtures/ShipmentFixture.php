<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-16
 * Time: 09:40
 */

namespace common\fixtures;

use yii\test\ActiveFixture;

class ShipmentFixture extends ActiveFixture
{

    public $modelClass = 'common\models\Shipment';

    public $dataFile = '@common/fixtures/data/data_fixed/shipment.php';
}