<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:20
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class SystemStateProvinceFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\SystemStateProvince';
    public $depends = [
        'common\fixtures\SystemCountryFixture'];
}