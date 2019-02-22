<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 09:29
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class StoreFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Store';
    public $depends = [
        'common\fixtures\CountryFixture',
        'common\fixtures\CurrencyFixture'
    ];
}