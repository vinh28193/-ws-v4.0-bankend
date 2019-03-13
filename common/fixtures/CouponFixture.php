<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:37
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class CouponFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Coupon';
    public $depends = [
        'common\fixtures\StoreFixture',
        'common\fixtures\UserFixture',
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/coupon.php';

}