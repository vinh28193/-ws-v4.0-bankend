<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-22
 * Time: 09:27
 */

namespace common\fixtures;

use yii\test\ActiveFixture;
class ManifestFixture extends ActiveFixture
{

    public $modelClass = 'common\models\Manifest';
    public $dataFile = '@common/fixtures/data/data_fixed/manifest.php';
}