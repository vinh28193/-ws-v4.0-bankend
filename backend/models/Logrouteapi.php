<?php

namespace backend\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "weshop_global_log_api_route".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $address
 * @property mixed $status
 */
class Logrouteapi extends ActiveRecord
{
    public static function collectionName()
    {
        return 'weshop_global_log_api_route';
    }

    public function attributes()
    {
        return [
            '_id',
            'name',
            'email',
            'address',
            'status',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'email', 'address', 'status'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'status' => 'Status',
        ];
    }
}
