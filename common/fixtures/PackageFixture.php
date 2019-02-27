<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 14:03
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class PackageFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Package';
    public $depends = [
        'common\fixtures\OrderFixture',
    ];
}