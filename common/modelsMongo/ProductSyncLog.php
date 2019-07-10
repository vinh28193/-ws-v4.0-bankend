<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "product_sync_log".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $row_id
 * @property mixed $item_type
 * @property mixed $item_id
 * @property mixed $item_sku
 * @property mixed $results
 * @property mixed $action
 * @property mixed $time
 */
class ProductSyncLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'product_sync_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'row_id',
            'item_type',
            'item_id',
            'item_sku',
            'results',
            'action',
            'time',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['row_id', 'item_type', 'item_id', 'item_sku', 'results', 'action', 'time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'row_id' => 'Row ID',
            'item_type' => 'Item Type',
            'item_id' => 'Item ID',
            'item_sku' => 'Item Sku',
            'results' => 'Results',
            'action' => 'Action',
            'time' => 'Time',
        ];
    }
}
