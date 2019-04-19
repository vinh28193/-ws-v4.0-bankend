<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $name Tên chương trình
 * @property string $code Tạo mã chạy chương trình duy nhất không trung
 * @property string $message message
 * @property string $description description
 * @property int $type 1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others
 * @property int $discount_calculator 1:%,2:fix value
 * @property string $discount_fee discount dùng cho phí gì
 * @property string $discount_amount giá trị được giảm
 * @property int $discount_type 1:price,2 weight,3 quantity
 * @property string $discount_max_amount giá trị max được giảm khi discount type %
 * @property int $refund_order_Id dùng với discountType = CouponRefund
 * @property int $allow_instalment 0/ not for instalment, 1 for all
 * @property int $allow_multiple 1:true, 0: false
 * @property int $allow_order 1:true, 0: false
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 */
class Promotion extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'discount_calculator', 'discount_type', 'refund_order_Id', 'allow_instalment', 'allow_multiple', 'allow_order', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['discount_amount', 'discount_max_amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 32],
            [['message'], 'string', 'max' => 255],
            [['discount_fee'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => 'Name',
            'code' => 'Code',
            'message' => 'Message',
            'description' => 'Description',
            'type' => 'Type',
            'discount_calculator' => 'Discount Calculator',
            'discount_fee' => 'Discount Fee',
            'discount_amount' => 'Discount Amount',
            'discount_type' => 'Discount Type',
            'discount_max_amount' => 'Discount Max Amount',
            'refund_order_Id' => 'Refund Order ID',
            'allow_instalment' => 'Allow Instalment',
            'allow_multiple' => 'Allow Multiple',
            'allow_order' => 'Allow Order',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\PromotionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\PromotionQuery(get_called_class());
    }
}
