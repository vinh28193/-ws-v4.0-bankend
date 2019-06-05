<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%promotion_user}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property int $customer_id Tên chương trình
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $is_used 1:Used
 * @property int $used_order_id Used for order id 
 * @property int $used_at Used at (timestamp)
 * @property int $created_at Created at (timestamp)
 * @property int $promotion_id
 */
class PromotionUser extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%promotion_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'customer_id'], 'required'],
            [['store_id', 'customer_id', 'status', 'is_used', 'used_order_id', 'used_at', 'created_at', 'promotion_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'status' => Yii::t('db', 'Status'),
            'is_used' => Yii::t('db', 'Is Used'),
            'used_order_id' => Yii::t('db', 'Used Order ID'),
            'used_at' => Yii::t('db', 'Used At'),
            'created_at' => Yii::t('db', 'Created At'),
            'promotion_id' => Yii::t('db', 'Promotion ID'),
        ];
    }
}
