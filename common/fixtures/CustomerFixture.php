<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:52
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class CustomerFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Customer';
    public $depends = [
        'common\fixtures\StoreFixture'
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/customer.php';

}