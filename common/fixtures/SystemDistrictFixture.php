<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:55
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class SystemDistrictFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\SystemDistrict';
    public $depends = [
        'common\fixtures\SystemCountryFixture',
        'common\fixtures\SystemStateProvinceFixture'
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/system_district.php';
}