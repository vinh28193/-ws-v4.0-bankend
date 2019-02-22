<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:31
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class AddressFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Address';
    public $depends = [
        'common\fixtures\SystemCountryFixture',
        'common\fixtures\SystemStateProvinceFixture',
        'common\fixtures\SystemDistrictFixture',
        'common\fixtures\CustomerFixture',
        'common\fixtures\StoreFixture',
    ];
}