<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "shopping_cart_upgrade".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $remove
 * @property mixed $type
 * @property mixed $key
 * @property mixed $value
 * @property mixed $uuid
 * @property mixed $create_at
 */
class ShoppingCartUpgrade extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['weshop_global_40', 'shopping_cart_upgrade'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'remove',
            'type',
            'key',
            'value',
            'uuid',
            'create_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remove', 'type', 'key', 'value', 'uuid', 'create_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'remove' => 'Remove',
            'type' => 'Type',
            'key' => 'Key',
            'value' => 'Value',
            'uuid' => 'Uuid',
            'create_at' => 'Create At',
        ];
    }
}
