<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/03/2019
 * Time: 11:02
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class ListAccountPurchaseFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\ListAccountPurchase';
    public $dataFile = '@common/fixtures/data/data_fixed/list_account_purchase.php';
}
