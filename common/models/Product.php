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
                'orderFeeReferenceAttribute' => 'product_id'
            ],
        ]);
    }

    public function createFormCart($item){
        $this->isNewRecord = true;
        $this->sku = $item['item_sku'];
        $this->parent_sku = $item['item_id'];
        $this->link_origin = $item['item_origin_url'];
        $this->getAdditionalFees()->mset($item['additionalFees']);
        $this->category_id = $item['category_id'];
        list($this->price_amount, $this->total_price_amount_local) = $this->getAdditionalFees()->getTotalAdditionFees();
        $this->quantity = $item['quantity'];

    }

}