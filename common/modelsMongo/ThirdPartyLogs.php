<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "order_logs".
 *
 * @property \MongoId|string $_id
 * @property mixed $provider
 * @property mixed $content
 * @property mixed $action;
 * @property mixed $date;
 * @property mixed $create_by;
 * @property mixed $create_time;
 * @property mixed $request;
 * @property mixed $response;
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
            'provider',
            'content',
            'action',
            'date',
            'create_by',
            'create_time',
            'request',
            'response'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provider', 'content', 'action', 'date', 'create_by', 'create_time', 'request', 'response'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'provider' => 'Provider',
            'content' => 'Content',
            'action' => 'action',
            'date' => 'Date',
            'create_by' => 'Create by',
            'create_time' => 'Create time',
            'request' => 'Request',
            'response' => 'Response'
        ];
    }
}
