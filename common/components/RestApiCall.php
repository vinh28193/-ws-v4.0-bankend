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

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $reflection = new \ReflectionClass($this);
        if($reflection->getShortName() === 'ActiveRecord'){
            return $behaviors;
        }

        $timestamp = [];
        if ($this->hasAttribute('created_at')) {
            $timestamp[self::EVENT_BEFORE_INSERT][] = 'created_at';
        }
        if ($this->hasAttribute('updated_at')) {
            $timestamp[self::EVENT_BEFORE_UPDATE][] = 'updated_at';
        }

        $behaviors = !empty($timestamp) ? array_merge($behaviors, [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => $timestamp,
            ],
        ]) : $behaviors;

        return $behaviors;
    }

    public function attributes()
    {
        return [
            '_id',
            'success',
            'created_at',
            'updated_at',
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
            [['success', 'created_at',
                'updated_at', 'path', 'data','date' ,'user_id',
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
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }
}
