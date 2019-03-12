<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "promotion_log".
 *
 * @property int $id ID
 * @property string $promotion_id
 * @property string $promotion_type PROMOTION/COUPON/XU
 * @property string $coupon_code
 * @property string $order_bin
 * @property string $revenue_xu Số xu kiếm được
 * @property string $discount_amount Số tiền giảm giá
 * @property int $customer_id
 * @property string $customer_email
 * @property string $status SUCCESS/FAIL
 * @property string $created_time Update qua behaviors tự động
 * @property string $updated_time Update qua behaviors tự động
 */
class PromotionLog extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['revenue_xu', 'discount_amount'], 'number'],
            [['customer_id', 'created_time', 'updated_time'], 'integer'],
            [['promotion_id'], 'string', 'max' => 11],
            [['promotion_type', 'coupon_code', 'order_bin', 'customer_email', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promotion_id' => 'Promotion ID',
            'promotion_type' => 'Promotion Type',
            'coupon_code' => 'Coupon Code',
            'order_bin' => 'Order Bin',
            'revenue_xu' => 'Revenue Xu',
            'discount_amount' => 'Discount Amount',
            'customer_id' => 'Customer ID',
            'customer_email' => 'Customer Email',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}
