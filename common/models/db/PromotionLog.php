<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%promotion_log}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $promotion_id Promotion ID
 * @property int $customer_id Customer ID
 * @property string $order_id Order ID
 * @property string $revenue_xu Số xu kiếm được
 * @property string $discount_amount Số tiền giảm giá
 * @property string $status SUCCESS/FAIL
 * @property int $created_at Created at (timestamp)
 */
class PromotionLog extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%promotion_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'customer_id', 'created_at'], 'integer'],
            [['revenue_xu', 'discount_amount'], 'number'],
            [['promotion_id'], 'string', 'max' => 11],
            [['order_id'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 255],
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
            'promotion_id' => Yii::t('db', 'Promotion ID'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'order_id' => Yii::t('db', 'Order ID'),
            'revenue_xu' => Yii::t('db', 'Revenue Xu'),
            'discount_amount' => Yii::t('db', 'Discount Amount'),
            'status' => Yii::t('db', 'Status'),
            'created_at' => Yii::t('db', 'Created At'),
        ];
    }
}
