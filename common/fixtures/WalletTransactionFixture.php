<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 14:05
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class WalletTransactionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\WalletTransaction';
    public $depends = [
        'common\fixtures\OrderFixture',
        'common\fixtures\SellerFixture',
        'common\fixtures\CategoryFixture',
        'common\fixtures\CategoryCustomPolicyFixture',
        'common\fixtures\ProductFixture',
        'common\fixtures\CustomerFixture',
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/wallet_transaction.php';
}
