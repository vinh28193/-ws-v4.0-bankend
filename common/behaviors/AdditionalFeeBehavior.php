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

    public $OrderFeeReferenceModelClass = false;

    public $OrderFeeReferenceAttribute = 'order_id';

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
     * @param \yii\db\AfterSaveEvent $event
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function evaluateOrderFee()
    {
        $storeAdditionFees = $this->owner->getStoreAdditionalFee();
        $attributes = $this->owner->getAdditionalFees();
        foreach ($attributes as $name => $arrayValue) {
            $totalAmount = 0;
            $totalLocalAmount = 0;
            foreach ($arrayValue as $value) {
                $totalAmount += $value['amount'];
                $totalLocalAmount += $value['amount_local'];
            }
            if (($storeAdditionFee = $storeAdditionFees[$name]) === null || !$storeAdditionFee instanceof StoreAdditionalFee) {
                Yii::warning("cannot evaluate '$name' cause not exist for StoreAdditional Fee config", __METHOD__);
                continue;
            }
            if ($storeAdditionFee->name === 'final_origin_fee' || $storeAdditionFee->name === 'final_local_fee') {
                continue;
            }
            $model = new OrderFee();
            $model->{$this->OrderFeeReferenceAttribute} = $this->owner->primaryKey;
            $model->currency = $storeAdditionFee->currency;
            $model->type_fee = $storeAdditionFee->name;
            $model->amount = $totalAmount;
            $model->amount_local = $totalLocalAmount;
            $model->save(false);
        }
        $this->flushTotalFee();


    }

    public function flushTotalFee()
    {

        $attributes = $this->owner->getAdditionalFees();
        $updateAttributes = [];
        $totalFee = 0;
        $totalFeeLocal = 0;
        foreach ($attributes as $name => $arrayValue) {
            $ownerName = "total_{$name}_local";
            $ownerFeeLocal = 0;
            foreach ($arrayValue as $value) {
                $ownerFeeLocal += isset($value['amount_local']) ? $value['amount_local'] : 0;
                $totalFee += isset($value['amount']) ? $value['amount'] : 0;
            }
            if ($this->owner->hasAttribute($ownerName)) {
                $updateAttributes[$ownerName] = $ownerFeeLocal;
            }
            $totalFeeLocal += $ownerFeeLocal;
        }

        $updateAttributes['total_amount_local'] = $totalFee;
        $updateAttributes['total_fee_amount_local'] = $totalFeeLocal;
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