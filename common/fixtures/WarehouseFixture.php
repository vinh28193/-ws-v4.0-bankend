<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 14:05
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class WarehouseFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Warehouse';
    public $depends = [
        'common\fixtures\SystemCountryFixture',
        'common\fixtures\SystemStateProvinceFixture',
        'common\fixtures\SystemDistrictFixture',
        'common\fixtures\StoreFixture',
    ];
}