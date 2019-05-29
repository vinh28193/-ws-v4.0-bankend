<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "shipment_logs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $shipment_id
 * @property mixed $shipment_code
 * @property mixed $type_log
 * @property mixed $message_log
 * @property mixed $created_at
 * @property mixed $more_data
 * @property mixed $user_id
 * @property mixed $user_email
 * @property mixed $user_name
 */
class ShipmentLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'shipment_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'shipment_id',
            'shipment_code',
            'type_log',
            'message_log',
            'created_at',
            'more_data',
            'user_id',
            'user_email',
            'user_name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipment_id', 'shipment_code', 'type_log', 'message_log', 'created_at', 'more_data', 'user_id', 'user_email', 'user_name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'shipment_id' => 'Shipment ID',
            'shipment_code' => 'Shipment Code',
            'type_log' => 'Type Log',
            'message_log' => 'Message Log',
            'created_at' => 'Created At',
            'more_data' => 'More Data',
            'user_id' => 'User Id',
            'user_email' => 'User Email',
            'user_name' => 'User Name',
        ];
    }
}
