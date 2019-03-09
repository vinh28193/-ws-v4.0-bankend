<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 13:42
 */

namespace common\models;

use common\components\AdditionalFeeTrait;
use common\components\StoreAdditionalFeeRegisterTrait;
use common\models\db\Product as DbProduct;
use yii\helpers\ArrayHelper;

class Product extends DbProduct
{
    use AdditionalFeeTrait;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            'orderFee' => [
                'class' => \common\behaviors\AdditionalFeeBehavior::className(),
            ],
        ]);
    }

}