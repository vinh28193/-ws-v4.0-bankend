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
            'date'
        ];
    }

    public function rules()
    {
        return [
            [['success', 'timestamp', 'path', 'data','date'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'success' => 'success True or False',
            'timestamp' => 'timestamp',
            'path' => 'Api Call by restfull',
            'data' => 'Data Respone',
            'date' => 'Date create data rest',
        ];
    }
}
