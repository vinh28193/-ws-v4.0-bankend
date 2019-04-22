<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_product".
 *
 * @property int $id
 * @property string $item_id
 * @property string $item_url
 * @property string $item_sku
 * @property string $name
 * @property string $image_origin
 * @property string $image
 * @property string $start_price
 * @property string $sell_price
 * @property int $weight
 * @property string $category_id
 * @property string $calculated_start_price
 * @property string $calculated_sell_price
 * @property int $rate_count
 * @property string $rate_star
 * @property string $category_name
 * @property string $start_time
 * @property string $end_time
 * @property string $provider
 * @property int $status
 * @property int $store_id
 * @property int $ws_product_group_id
 * @property int $sort
 * @property string $create_time
 * @property string $update_time
 *
 * @property WsProductGroup $wsProductGroup
 */
class WsProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_product';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_cms');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_price', 'sell_price', 'calculated_start_price', 'calculated_sell_price'], 'number'],
            [['weight', 'rate_count', 'status', 'store_id', 'ws_product_group_id', 'sort'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['item_id', 'item_sku', 'provider'], 'string', 'max' => 50],
            [['item_url'], 'string', 'max' => 500],
            [['name', 'image_origin', 'image', 'rate_star', 'category_name'], 'string', 'max' => 255],
            [['category_id'], 'string', 'max' => 25],
            [['start_time', 'end_time'], 'string', 'max' => 30],
            [['ws_product_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsProductGroup::className(), 'targetAttribute' => ['ws_product_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'item_url' => 'Item Url',
            'item_sku' => 'Item Sku',
            'name' => 'Name',
            'image_origin' => 'Image Origin',
            'image' => 'Image',
            'start_price' => 'Start Price',
            'sell_price' => 'Sell Price',
            'weight' => 'Weight',
            'category_id' => 'Category ID',
            'calculated_start_price' => 'Calculated Start Price',
            'calculated_sell_price' => 'Calculated Sell Price',
            'rate_count' => 'Rate Count',
            'rate_star' => 'Rate Star',
            'category_name' => 'Category Name',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'provider' => 'Provider',
            'status' => 'Status',
            'store_id' => 'Store ID',
            'ws_product_group_id' => 'Ws Product Group ID',
            'sort' => 'Sort',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProductGroup()
    {
        return $this->hasOne(WsProductGroup::className(), ['id' => 'ws_product_group_id']);
    }
}
