<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 10:26
 */

namespace common\behaviors;

use common\models\OrderFee;
use common\models\StoreAdditionalFee;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class AdditionalFeeBehavior
 * @package common\behaviors
 */
class AdditionalFeeBehavior extends \yii\base\Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;
    /**
     * @var string
     */

    public $orderFeeReferenceAttribute = 'order_id';

    public $originCurrencyReferenceAttribute = 'origin_currency';

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'evaluateOrderFee'
        ];
    }

    /**
     *
     */
    public function evaluateOrderFee()
    {
        /** @var  $getAdditionalFees  \common\components\AdditionalFeeCollection*/
        $getAdditionalFees = $this->owner->getAdditionalFees();
        $updateAttributes = [];
        $totalFee = 0;
        $totalFeeLocal = 0;
        foreach ($getAdditionalFees->keys() as $key) {
            if (($storeAdditionFee = $getAdditionalFees->getStoreAdditionalFeeByKey($key)) === null || !$storeAdditionFee instanceof StoreAdditionalFee) {
                Yii::warning("cannot evaluate '$key' cause not exist for StoreAdditional Fee config", __METHOD__);
                continue;
            }
            if ($storeAdditionFee->name === 'final_origin_fee' || $storeAdditionFee->name === 'final_local_fee') {
                continue;
            }
            $ownerName = "total_{$key}_local";

            $model = new OrderFee();
            $model->{$this->orderFeeReferenceAttribute} = $this->owner->primaryKey;
            $model->currency = $storeAdditionFee->currency;
            $model->type_fee = $storeAdditionFee->name;
            list($model->amount,$model->amount_local) = $getAdditionalFees->getTotalAdditionFees($key);
            $model->save(false);
            if ($this->owner->hasAttribute($ownerName)) {
                $updateAttributes[$ownerName] = $model->amount_local;
            }
            $totalFee += $model->amount;
            $totalFeeLocal +=$model->amount_local;
        }
        if ($this->owner->hasAttribute('total_amount_local')) {
            $updateAttributes['total_amount_local'] = $totalFee;
        }
        if ($this->owner->hasAttribute('total_fee_amount_local')) {
            $updateAttributes['total_fee_amount_local'] = $totalFeeLocal;
        }
        $this->owner->updateAttributes($updateAttributes);
        $model = new OrderFee();
        $model->amount = $totalFee;
        $model->amount_local = $totalFeeLocal;
        $model->order_id = $this->owner->primaryKey;
        $model->currency = Yii::$app->storeManager->store->currency;
        $model->discount_amount = 0;
        $model->type_fee = 'total_fee';
        $model->save(false);


    }
}