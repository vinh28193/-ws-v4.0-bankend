<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "exchange_rate_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $ex_id
 * @property mixed $content
 * @property mixed $username
 * @property mixed $ip
 * @property mixed $employee
 * @property mixed $updated_at
 */
class ExchangeRateLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'exchange_rate_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'ex_id',
            'content',
            'username',
            'ip',
            'employee',
            'updated_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ex_id', 'content', 'username', 'ip', 'employee', 'updated_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'ex_id' => 'Ex ID',
            'content' => 'Content',
            'username' => 'Username',
            'ip' => 'Ip',
            'employee' => 'Employee',
            'updated_at' => 'Updated At',
        ];
    }
}
