<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "packing_logs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $manifest_id
 * @property mixed $manifest_code
 * @property mixed $package_id
 * @property mixed $package_code
 * @property mixed $type_log
 * @property mixed $message_log
 * @property mixed $package_code_reference
 * @property mixed $tracking_code_reference
 * @property mixed $product_id
 * @property mixed $delivery_note_code
 * @property mixed $created_at
 * @property mixed $more_data
 * @property mixed $user_id
 * @property mixed $user_email
 * @property mixed $user_name
 * @property mixed $role
 * @property mixed $request_ip
 */
class PackingLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'packing_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'manifest_id',
            'manifest_code',
            'package_id',
            'package_code',
            'type_log',
            'message_log',
            'package_code_reference',
            'tracking_code_reference',
            'delivery_note_code',
            'created_at',
            'more_data',
            'user_id',
            'user_email',
            'user_name',
            'product_id',
            'role',
            'request_ip',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manifest_id', 'role', 'request_ip', 'manifest_code', 'package_id', 'package_code', 'type_log', 'message_log', 'package_code_reference', 'tracking_code_reference', 'delivery_note_code', 'created_at', 'more_data', 'user_id', 'user_email', 'user_name','product_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'manifest_id' => 'Manifest ID',
            'manifest_code' => 'Manifest Code',
            'package_id' => 'Package ID',
            'package_code' => 'Package Code',
            'type_log' => 'Type Log',
            'message_log' => 'Message Log',
            'package_code_reference' => 'Package Code Reference',
            'tracking_code_reference' => 'Tracking Code Reference',
            'delivery_note_code' => 'Delivery Note Code',
            'created_at' => 'Created At',
            'more_data' => 'More Data',
            'user_id' => 'User Id',
            'user_email' => 'User Email',
            'user_name' => 'User Name',
            'product_id' => 'Product Id',
            'role' => 'Role',
            'request_ip' => 'request_ip',
        ];
    }
}
