<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 20:53
 */

namespace common\fixtures;


class PackageItemRawFixture extends \yii\test\ActiveFixture
{

    public $modelClass = 'common\models\PackageItemRaw';

    public $dataFile = '@common/fixtures/data/data_fixed/package_item_raw.php';
}