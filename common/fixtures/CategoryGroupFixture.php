<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 1:19 CH
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class CategoryGroupFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\CategoryGroup';
    public $dataFile = '@common/fixtures/data/data_fixed/category_group.php';
}
