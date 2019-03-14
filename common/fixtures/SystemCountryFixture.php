<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 09:23
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class SystemCountryFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\SystemCountry';
    public $dataFile = '@common/fixtures/data/data_fixed/country.php';
}