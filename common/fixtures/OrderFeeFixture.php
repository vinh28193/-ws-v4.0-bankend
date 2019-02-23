<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 11:15
 */

namespace common\fixtures;


class OrderFeeFixture extends \yii\test\ActiveFixture
{
    public $modelClass = 'common\models\OrderFee';
    public $depends = [
        'common\fixtures\OrderFixture',
    ];
}