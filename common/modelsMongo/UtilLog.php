<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "util_log".
 *
 * @property \MongoId|string $_id
 * @property mixed $action
 * @property mixed $request
 * @property mixed $respone
 * @property mixed $time
 */
class UtilLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'util_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'action',
            'request',
            'respone',
            'time',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'request', 'respone', 'time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'action' => Yii::t('app', 'Action'),
            'request' => Yii::t('app', 'Request'),
            'respone' => Yii::t('app', 'Respone'),
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
