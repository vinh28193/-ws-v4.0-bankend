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

            $attributes = $this->owner->getAdditionalFees();
            Yii::info(array_keys($attributes),'changedAttributes');
            foreach ($attributes as $name => $arrayValue) {
                $totalAmount = 0;
                $totalLocalAmount = 0;
                foreach ($arrayValue as $value){
                    $totalAmount += $value['amount'];
                    $totalLocalAmount += $value['local_amount'];
                }
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
                    $model = OrderFee::findOne($record);
                    $value = $valueNew;
                }
                if (($event->name === ActiveRecord::EVENT_AFTER_UPDATE && isset($model) && $model === null) || $event->name === ActiveRecord::EVENT_AFTER_INSERT) {
                    $model = new OrderFee($record);
                }
                $model->amount = $totalAmount ;
                $model->amount_local = $totalLocalAmount;
                if ($model->isNewRecord && $this->owner->hasAttribute($this->originCurrencyReferenceAttribute)) {
                    $model->currency = $this->owner->{$this->originCurrencyReferenceAttribute};
                }
                $model->detachBehavior('orderFee');
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

//        $allOrderFees = $this->owner->getAdditionalFees();
//        foreach ($allOrderFees as $orderFee){
//            /** @var $orderFee OrderFee*/
//            $storeAdditionFee = array_filter($this->owner->getStoreAdditionalFee(), function ($e) use ($orderFee){
//                /** @var $e StoreOrderFee*/
//                return $e->id === $orderFee->type_fee;
//            });
//
//            if (empty($storeAdditionFee) || ($storeAdditionFee = array_values($storeAdditionFee)[0]) === null || !$storeAdditionFee instanceof StoreOrderFee) {
//                Yii::warning("cannot calculator  with {$orderFee->id} cause not exist on StoreOrderFee config", __METHOD__);
//                continue;
//            }
//            if(in_array($storeAdditionFee->name,['origin_price','origin_tax','origin_shipping_fee'])){
//                $totalOriginFee += $orderFee->amount_local;
//            }
//            $totalLocalFee += $orderFee->amount_local;
//        }

        Yii::info([$totalOriginFee,$totalLocalFee], 'finalCalculator');
    }

    public function insertOrderFee($event){
        if (!$this->owner instanceof AdditionalFeeInterface) {
            return;
        }
        $attributes = $this->owner->getAdditionalFees();

    }

}