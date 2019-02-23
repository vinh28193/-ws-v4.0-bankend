<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 10:26
 */

namespace common\behaviors;

use common\components\AdditionalFeeInterface;
use common\models\OrderFee;
use common\models\StoreAdditionalFee;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class OrderFeeBehavior
 * @package common\behaviors
 */

class OrderFeeBehavior extends \yii\base\Behavior
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
            ActiveRecord::EVENT_AFTER_INSERT => 'evaluateOrderFee',
            ActiveRecord::EVENT_AFTER_UPDATE => 'evaluateOrderFee',
        ];
    }

    /**
     * @param \yii\db\AfterSaveEvent $event
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function evaluateOrderFee($event)
    {
        if ($event->name === ActiveRecord::EVENT_AFTER_UPDATE && empty($event->changedAttributes)) {
            return;
        }
        $storeAdditionFees = $this->owner->getStoreAdditionalFee();
        if ($this->owner instanceof AdditionalFeeInterface) {

            $attributes = $event->name === ActiveRecord::EVENT_AFTER_UPDATE ? $event->changedAttributes : $this->owner->getOrderFees();
            Yii::info(array_keys($attributes),'changedAttributes');
            foreach ($attributes as $name => $value) {
                $value = $value == null ? 0 : $value;
                if (($storeAdditionFee = $storeAdditionFees[$name]) === null || !$storeAdditionFee instanceof StoreAdditionalFee) {
                    Yii::warning("cannot " . ($event->name === ActiveRecord::EVENT_BEFORE_UPDATE ? "update" : "insert") . " '$name' cause not exist on StoreOrderFee config", __METHOD__);
                    continue;
                }
                if($storeAdditionFee->name === 'final_origin_fee' || $storeAdditionFee->name === 'final_local_fee'){
                    continue;
                }
                $record = [
                    $this->OrderFeeReferenceAttribute => $this->owner->primaryKey,
                    'type_fee' => $storeAdditionFee->name
                ];

                /** @var $model OrderFee */
                if ($event->name === ActiveRecord::EVENT_AFTER_UPDATE) {
                    $valueNew = $this->owner->getAttribute($name); // new value
                    Yii::info("action will be update $name from $value to $valueNew ",__METHOD__);
                    $model = OrderFee::findOne($record);
                    $value = $valueNew;
                }
                if (($event->name === ActiveRecord::EVENT_AFTER_UPDATE && isset($model) && $model === null) || $event->name === ActiveRecord::EVENT_AFTER_INSERT) {
                    $model = new OrderFee($record);
                }
                $model->amount = $value;
                $model->amount_local = $this->owner->getExchangeRate() * $value;
                if ($model->isNewRecord && $this->owner->hasAttribute($this->originCurrencyReferenceAttribute)) {
                    $model->currency = $this->owner->{$this->originCurrencyReferenceAttribute};
                }
                //$model->detachBehavior('orderFee');
                $model->save(false);
            }

        } else if ($this->OrderFeeReferenceModelClass !== null && is_string($this->OrderFeeReferenceModelClass)) {
            $owner = $this->owner;
            if ($owner instanceof OrderFee) {

                if (($storeAdditionFee = $storeAdditionFees[$owner->type_fee]) === null || !$storeAdditionFee instanceof StoreAdditionalFee) {
                    Yii::warning("cannot " . ($event->name === ActiveRecord::EVENT_BEFORE_UPDATE ? "update" : "insert") . " '$name' cause not exist on StoreOrderFee config", __METHOD__);
                    return;
                }

                if($storeAdditionFee->name === 'final_origin_fee' || $storeAdditionFee->name === 'final_local_fee'){
                    return;
                }
                /** @var $class ActiveRecord */
                $class = $this->OrderFeeReferenceModelClass;
                /** @var $model [[self::$OrderFeeReferenceAttribute]] */
                if (($model = $class::findOne($owner->{$this->OrderFeeReferenceAttribute})) === null) {
                    return;
                }

                $results[$storeAdditionFee->name] = $owner->amount_local;
//                $results['final_origin_fee'] = $owner->getTotalOrderFee(['origin_price','origin_tax', 'origin_shipping_fee']);
//                $results['final_local_fee'] = $owner->getTotalOrderFee();
                if($model instanceof AdditionalFeeInterface){
                    $model->setAdditionalFees($results);
                }elseif ($model instanceof ActiveRecord){
                    $model->setAttributes($results);
                }else {
                    foreach ($results as $name => $value){
                        try {
                            $model->$name = $value;
                        } catch (\Exception $exception){
                            Yii::warning("cannot set property '$name' cause not exist on ".get_class($model), __METHOD__);
                            continue;
                        }
                    }
                }
                $model->detachBehavior('orderFee');
                $model->update(false);
            } else {
                return;
            }

        } else {
            return;
        }

        $totalOriginFee = 0;
        $totalLocalFee = 0;
        $allOrderFees = OrderFee::findAll([$this->OrderFeeReferenceAttribute => $this->owner instanceof OrderFee ? $this->owner->{$this->OrderFeeReferenceAttribute} : $this->owner->getPrimaryKey()]);
        foreach ($allOrderFees as $additionFee){
            /** @var $additionFee OrderFee*/
            $storeAdditionFee = array_filter($this->owner->getStoreOrderFee(), function ($e) use ($additionFee){
                /** @var $e StoreOrderFee*/
                return $e->id === $additionFee->store_additional_fee_id;
            });

            if (empty($storeAdditionFee) || ($storeAdditionFee = array_values($storeAdditionFee)[0]) === null || !$storeAdditionFee instanceof StoreOrderFee) {
                Yii::warning("cannot calculator  with {$additionFee->id} cause not exist on StoreOrderFee config", __METHOD__);
                continue;
            }
            if(in_array($storeAdditionFee->name,['origin_price','origin_tax','origin_shipping_fee'])){
                $totalOriginFee += $additionFee->origin_value;
            }
            $totalLocalFee += $additionFee->local_value;
        }

        Yii::info([$totalOriginFee,$totalLocalFee], 'finalCalculator');
    }

}