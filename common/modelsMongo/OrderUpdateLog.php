<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "order_update_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $action
 * @property mixed $order_code
 * @property mixed $dirty_attribute
 * @property mixed $diff_value
 * @property mixed $action
 * @property mixed $create_by
 * @property mixed $create_at
 */
class OrderUpdateLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'order_update_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'action',
            'order_code',
            'dirty_attribute',
            'diff_value',
            'action',
            'create_by',
            'create_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'order_code', 'dirty_attribute', 'diff_value', 'action', 'create_by', 'create_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'action' => 'Action',
            'order_code' => 'Order Code',
            'dirty_attribute' => 'Dirty Attribute',
            'diff_value' => 'Diff Value',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
        ];
    }
}
