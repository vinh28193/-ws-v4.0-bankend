<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/03/2019
 * Time: 11:18
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class PurchasePaymentCardFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\PurchasePaymentCard';
    public $dataFile = '@common/fixtures/data/data_fixed/list_card.php';
}
