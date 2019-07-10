<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "order_logs".
 *
 * @property \MongoId|string $_id
 * @property mixed $date;
 * @property mixed $provider
 * @property mixed $action;
 * @property mixed $message;
 * @property mixed $content;
 * @property mixed $request;
 * @property mixed $response;
 * @property mixed $create_by;
 * @property mixed $create_time;
 */
class ThirdPartyLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'third_party_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'date',
            'provider',
            'action',
            'message',
            'content',
            'request',
            'response',
            'create_by',
            'create_time',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'provider', 'action', 'message', 'content', 'request', 'response', 'create_by', 'create_time'], 'safe']
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
            'provider' => 'Provider',
            'action' => 'action',
            'message' => 'Message',
            'content' => 'Content',
            'request' => 'Request',
            'response' => 'Response',
            'create_by' => 'Create by',
            'create_time' => 'Create time',
        ];
    }
}
