<?php

namespace common\components;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "rest_api_call".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $address
 * @property mixed $status
 */
class RestApiCall extends ActiveRecord
{
    public static function collectionName()
    {
        return 'rest_api_call';
    }

    public function attributes()
    {
        return [
            '_id',
            'success',
            'timestamp',
            'path',
            'data',
            'date',
            'user_id',
            'user_email',
            'user_name',
            'user_app',
            'user_request_suorce',
            'request_ip'
        ];
    }

    public function rules()
    {
        return [
            [['success', 'timestamp', 'path', 'data','date' ,'user_id',
                'user_email',
                'user_name',
                'user_app',
                'user_request_suorce',
                'request_ip'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'success' => 'success true or false',
            'timestamp' => 'timestamp',
            'path' => 'Api Call by restfull',
            'data' => 'Data Respone',
            'date' => 'Date create data rest',
            'user_id' => 'User request User id via API ',
            'user_email' => 'USer Email ',
            'user_name' => 'User Name request via api',
            'user_app' => 'User App request via api ',
            'user_request_suorce' => 'request suorce APP/WEB_API_FRONTEND/WB_API_BACK_END ',
            'request_ip' => 'IP request ',
        ];
    }
}
