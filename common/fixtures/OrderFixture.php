<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:55
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Order';
    public $depends = [
        'common\fixtures\SystemCountryFixture',
        'common\fixtures\SystemStateProvinceFixture',
        'common\fixtures\SystemDistrictFixture',
        'common\fixtures\CustomerFixture',
        'common\fixtures\StoreFixture',
        'common\fixtures\AddressFixture',
        'common\fixtures\UserFixture',
        'common\fixtures\SellerFixture',
        'common\fixtures\CouponFixture',
    ];
}