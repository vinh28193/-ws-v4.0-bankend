<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "Weshop_log_40".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $id
 * @property mixed $name
 * @property mixed $label
 * @property mixed $type
 * @property mixed $target
 * @property mixed $target_id
 * @property mixed $amount
 * @property mixed $local_amount
 * @property mixed $currency
 * @property mixed $exchange_rate
 * @property mixed $action
 * @property mixed $action_by
 * @property mixed $action_at
 */
class AdditionalFeeLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['additional_fee_log', 'Weshop_log_40'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'label',
            'type',
            'target',
            'target_id',
            'amount',
            'local_amount',
            'currency',
            'exchange_rate',
            'action',
            'action_by',
            'action_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'label', 'type', 'target', 'target_id', 'amount', 'local_amount', 'currency', 'exchange_rate', 'action', 'action_by', 'action_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => 'Id',
            'name' => 'Name',
            'label' => 'Label',
            'type' => 'Type',
            'target' => 'Target',
            'target_id' => 'Target ID',
            'amount' => 'Amount',
            'local_amount' => 'Local Amount',
            'currency' => 'Currency',
            'exchange_rate' => 'Exchange Rate',
            'action' => 'Action',
            'action_by' => 'Action By',
            'action_at' => 'Action At',
        ];
    }
}
