<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "delivery_note_logs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $delivery_note_id
 * @property mixed $delivery_note_code
 * @property mixed $type_log
 * @property mixed $message_log
 * @property mixed $delivery_note_code_reference
 * @property mixed $package_code_reference
 * @property mixed $shipment_id
 * @property mixed $shipment_code
 * @property mixed $created_at
 * @property mixed $more_data
 * @property mixed $user_id
 * @property mixed $user_email
 * @property mixed $user_name
 */
class DeliveryNoteLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'delivery_note_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'delivery_note_id',
            'delivery_note_code',
            'type_log',
            'message_log',
            'delivery_note_code_reference',
            'shipment_id',
            'shipment_code',
            'created_at',
            'more_data',
            'user_id',
            'user_email',
            'user_name',
            'package_code_reference',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_note_id','package_code_reference', 'delivery_note_code', 'type_log', 'message_log', 'delivery_note_code_reference', 'shipment_id', 'shipment_code', 'created_at', 'more_data', 'user_id', 'user_email', 'user_name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'delivery_note_id' => 'Delivery Note ID',
            'delivery_note_code' => 'Delivery Note Code',
            'type_log' => 'Type Log',
            'message_log' => 'Message Log',
            'delivery_note_code_reference' => 'Delivery Note Code Reference',
            'shipment_id' => 'Shipment ID',
            'shipment_code' => 'Shipment Code',
            'created_at' => 'Created At',
            'more_data' => 'More Data',
            'user_id' => 'User Id',
            'user_email' => 'User Email',
            'user_name' => 'User Name',
            'package_code_reference' => 'Package Code Reference',
        ];
    }
}
