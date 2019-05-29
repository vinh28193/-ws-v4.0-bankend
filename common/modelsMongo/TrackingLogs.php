<?php

namespace common\modelsMongo;

/**
 * This is the model class for collection "tracking_logs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $tracking_code
 * @property mixed $type_log
 * @property mixed $message_log
 * @property mixed $tracking_code_ws
 * @property mixed $tracking_code_reference
 * @property mixed $manifest_id
 * @property mixed $manifest_code
 * @property mixed $package_code
 * @property mixed $product_id
 * @property mixed $order_id
 * @property mixed $created_at
 * @property mixed $more_data
 * @property mixed $current_status
 * @property mixed $user_id
 * @property mixed $user_email
 * @property mixed $user_name
 */
class TrackingLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'tracking_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'tracking_code',
            'type_log',
            'message_log',
            'tracking_code_ws',
            'manifest_id',
            'manifest_code',
            'package_code',
            'product_id',
            'order_id',
            'created_at',
            'more_data',
            'current_status',
            'user_id',
            'user_email',
            'user_name',
            'tracking_code_reference',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code', 'current_status', 'type_log', 'message_log', 'tracking_code_ws', 'manifest_id', 'manifest_code', 'package_code', 'product_id', 'order_id', 'created_at', 'more_data', 'user_id', 'user_email', 'user_name', 'tracking_code_reference'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'tracking_code' => 'Tracking Code',
            'type_log' => 'Type Log',
            'message_log' => 'Message Log',
            'tracking_code_ws' => 'Tracking Code Ws',
            'manifest_id' => 'Manifest ID',
            'manifest_code' => 'Manifest Code',
            'package_code' => 'Package Code',
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'created_at' => 'Created At',
            'more_data' => 'More Data',
            'current_status' => 'Current Status',
            'user_id' => 'User Id',
            'user_email' => 'User Email',
            'user_name' => 'User Name',
            'tracking_code_reference' => 'Tracking Code Reference',
        ];
    }
}
