<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:44
 */

namespace common\fixtures;

use yii\test\ActiveFixture;
class DraftPackageItemFixture extends  ActiveFixture
{

    public $modelClass = 'common\models\draft\Package';

    public $dataFile = '@common/fixtures/data/data_fixed/draft_package_item.php';
}