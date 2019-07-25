<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "grpc_client_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $date
 * @property mixed $action
 * @property mixed $client_name
 * @property mixed $client_action
 * @property mixed $host_name
 * @property mixed $user_id
 * @property mixed $country
 * @property mixed $request
 * @property mixed $response
 * @property mixed $create_at
 */
class GrpcClientLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'grpc_client_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'date',
            'action',
            'client_name',
            'client_action',
            'host_name',
            'user_id',
            'country',
            'request',
            'response',
            'create_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'action', 'client_name', 'client_action', 'host_name', 'user_id', 'country', 'request', 'response', 'create_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'date' => 'Date',
            'action' => 'Action',
            'host_name' => 'Host Name',
            'user_id' => 'User ID',
            'country' => 'Country',
            'request' => 'Request',
            'response' => 'Response',
            'create_at' => 'Create At',
        ];
    }
}
